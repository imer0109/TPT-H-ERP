<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'evaluated_by',
        'quality_rating',
        'delivery_rating',
        'responsiveness_rating',
        'pricing_rating',
        'comments',
        'overall_score',
        'evaluation_date'
    ];

    protected $casts = [
        'quality_rating' => 'integer',
        'delivery_rating' => 'integer',
        'responsiveness_rating' => 'integer',
        'pricing_rating' => 'integer',
        'overall_score' => 'decimal:2',
        'evaluation_date' => 'date'
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function calculateOverallScore()
    {
        $ratings = [
            $this->quality_rating,
            $this->delivery_rating,
            $this->responsiveness_rating,
            $this->pricing_rating
        ];
        
        // Filter out null values
        $ratings = array_filter($ratings, function($rating) {
            return $rating !== null;
        });
        
        if (count($ratings) > 0) {
            return array_sum($ratings) / count($ratings);
        }
        
        return null;
    }

    /**
     * Get rating description based on score
     */
    public function getRatingDescriptionAttribute()
    {
        if (!$this->overall_score) {
            return 'Non évalué';
        }

        return match(true) {
            $this->overall_score >= 4.5 => 'Excellent',
            $this->overall_score >= 3.5 => 'Bon',
            $this->overall_score >= 2.5 => 'Satisfaisant',
            $this->overall_score >= 1.5 => 'Passable',
            default => 'Insuffisant'
        };
    }

    /**
     * Get rating color based on score
     */
    public function getRatingColorAttribute()
    {
        if (!$this->overall_score) {
            return 'gray';
        }

        return match(true) {
            $this->overall_score >= 4.5 => 'green',
            $this->overall_score >= 3.5 => 'blue',
            $this->overall_score >= 2.5 => 'yellow',
            $this->overall_score >= 1.5 => 'orange',
            default => 'red'
        };
    }

    /**
     * Get star rating display
     */
    public function getStarRatingAttribute()
    {
        if (!$this->overall_score) {
            return 0;
        }
        
        return round($this->overall_score);
    }

    protected static function booted()
    {
        static::saving(function ($rating) {
            $rating->overall_score = $rating->calculateOverallScore();
        });
    }

    /**
     * Create automatic evaluation based on supplier performance metrics
     */
    public static function createAutomaticEvaluation(Fournisseur $supplier)
    {
        // Calculate ratings based on supplier performance
        $qualityRating = self::calculateQualityRating($supplier);
        $deliveryRating = self::calculateDeliveryRating($supplier);
        $responsivenessRating = self::calculateResponsivenessRating($supplier);
        $pricingRating = self::calculatePricingRating($supplier);
        
        // Create the rating
        $rating = new self();
        $rating->fournisseur_id = $supplier->id;
        $rating->evaluated_by = null; // System evaluation
        $rating->quality_rating = $qualityRating;
        $rating->delivery_rating = $deliveryRating;
        $rating->responsiveness_rating = $responsivenessRating;
        $rating->pricing_rating = $pricingRating;
        $rating->comments = 'Évaluation automatique basée sur les performances du fournisseur';
        $rating->evaluation_date = now();
        $rating->save();
        
        // Update supplier's average rating
        $supplier->note_moyenne = $supplier->supplierRatings()->avg('overall_score');
        $supplier->nombre_evaluations = $supplier->supplierRatings()->count();
        $supplier->save();
        
        return $rating;
    }

    /**
     * Calculate quality rating based on delivered items vs ordered items
     */
    private static function calculateQualityRating(Fournisseur $supplier)
    {
        // Get all deliveries for this supplier
        $deliveries = $supplier->supplierDeliveries;
        
        if ($deliveries->isEmpty()) {
            return null;
        }
        
        // Calculate quality score based on delivery accuracy
        $totalDeliveries = $deliveries->count();
        $accurateDeliveries = 0;
        
        foreach ($deliveries as $delivery) {
            // Assuming we have a way to measure quality in deliveries
            // This would depend on your business logic
            if (!isset($delivery->quality_issues) || $delivery->quality_issues == 0) {
                $accurateDeliveries++;
            }
        }
        
        // Convert to 1-5 rating scale
        $accuracyRate = $accurateDeliveries / $totalDeliveries;
        return max(1, min(5, round($accuracyRate * 5)));
    }

    /**
     * Calculate delivery rating based on on-time deliveries
     */
    private static function calculateDeliveryRating(Fournisseur $supplier)
    {
        // Get all orders for this supplier
        $orders = $supplier->supplierOrders;
        
        if ($orders->isEmpty()) {
            return null;
        }
        
        $totalOrders = $orders->count();
        $onTimeDeliveries = 0;
        
        foreach ($orders as $order) {
            // Check if delivery was on time
            if ($order->date_livraison_effective && $order->date_livraison_prevue) {
                if ($order->date_livraison_effective <= $order->date_livraison_prevue) {
                    $onTimeDeliveries++;
                }
            }
        }
        
        // Convert to 1-5 rating scale
        $onTimeRate = $onTimeDeliveries / $totalOrders;
        return max(1, min(5, round($onTimeRate * 5)));
    }

    /**
     * Calculate responsiveness rating based on communication speed
     */
    private static function calculateResponsivenessRating(Fournisseur $supplier)
    {
        // Get all issues for this supplier
        $issues = $supplier->supplierIssues;
        
        if ($issues->isEmpty()) {
            return null;
        }
        
        $totalIssues = $issues->count();
        $resolvedIssues = 0;
        $quickResponseIssues = 0;
        
        foreach ($issues as $issue) {
            // Check if issue was resolved
            if ($issue->statut === 'closed') {
                $resolvedIssues++;
                
                // Check if issue was resolved quickly (within 3 days)
                $created = $issue->created_at;
                $resolved = $issue->updated_at;
                $daysToResolve = $resolved->diffInDays($created);
                
                if ($daysToResolve <= 3) {
                    $quickResponseIssues++;
                }
            }
        }
        
        // Combine resolution rate and quick response rate
        $resolutionRate = $resolvedIssues / $totalIssues;
        $responseRate = $quickResponseIssues / max(1, $resolvedIssues);
        
        // Weighted average: 60% resolution rate, 40% quick response rate
        $combinedRate = ($resolutionRate * 0.6) + ($responseRate * 0.4);
        
        return max(1, min(5, round($combinedRate * 5)));
    }

    /**
     * Calculate pricing rating based on competitiveness
     */
    private static function calculatePricingRating(Fournisseur $supplier)
    {
        // For simplicity, we'll use a basic approach
        // In a real system, you might compare prices with market rates
        
        // Get average order amount for this supplier
        $avgOrderAmount = $supplier->supplierOrders()->avg('montant_ttc');
        
        if (!$avgOrderAmount) {
            return null;
        }
        
        // Compare with average order amount across all suppliers
        $overallAvg = SupplierOrder::avg('montant_ttc');
        
        if (!$overallAvg) {
            return null;
        }
        
        // Calculate competitive ratio
        $ratio = $avgOrderAmount / $overallAvg;
        
        // Convert to rating (lower prices = higher ratings)
        if ($ratio <= 0.8) {
            return 5; // Very competitive
        } elseif ($ratio <= 0.9) {
            return 4; // Competitive
        } elseif ($ratio <= 1.0) {
            return 3; // Average
        } elseif ($ratio <= 1.1) {
            return 2; // Slightly expensive
        } else {
            return 1; // Expensive
        }
    }
}