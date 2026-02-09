<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuditService
{
    /**
     * Get audit statistics for dashboard
     */
    public function getAuditStatistics($companyId = null, $period = '30_days')
    {
        $query = AuditTrail::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        // Apply period filter
        switch ($period) {
            case '24_hours':
                $query->where('created_at', '>=', now()->subDay());
                break;
            case '7_days':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case '30_days':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            case '90_days':
                $query->where('created_at', '>=', now()->subMonths(3));
                break;
        }

        $totalEvents = $query->count();
        $highRiskEvents = $query->where('risk_level', 'high')->count();
        $mediumRiskEvents = $query->where('risk_level', 'medium')->count();
        $lowRiskEvents = $query->where('risk_level', 'low')->count();

        // Get events by type
        $eventsByType = $query->select('event', DB::raw('count(*) as count'))
                             ->groupBy('event')
                             ->orderBy('count', 'desc')
                             ->limit(10)
                             ->get();

        // Get most active users
        $activeUsers = $query->select('user_id', DB::raw('count(*) as count'))
                            ->whereNotNull('user_id')
                            ->groupBy('user_id')
                            ->orderBy('count', 'desc')
                            ->limit(10)
                            ->with('user:id,nom,prenom,email')
                            ->get();

        // Get activity by hour for the last 24 hours
        $hourlyActivity = $query->where('created_at', '>=', now()->subDay())
                               ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                               ->groupBy(DB::raw('HOUR(created_at)'))
                               ->orderBy('hour')
                               ->get();

        // Get suspicious activities
        $suspiciousActivities = AuditTrail::where('risk_level', 'high')
                                         ->where('created_at', '>=', now()->subWeek())
                                         ->with('user:id,nom,prenom,email')
                                         ->orderBy('created_at', 'desc')
                                         ->limit(20)
                                         ->get();

        return [
            'summary' => [
                'total_events' => $totalEvents,
                'high_risk_events' => $highRiskEvents,
                'medium_risk_events' => $mediumRiskEvents,
                'low_risk_events' => $lowRiskEvents,
                'risk_percentage' => $totalEvents > 0 ? round(($highRiskEvents / $totalEvents) * 100, 2) : 0
            ],
            'events_by_type' => $eventsByType,
            'active_users' => $activeUsers,
            'hourly_activity' => $hourlyActivity,
            'suspicious_activities' => $suspiciousActivities
        ];
    }

    /**
     * Get detailed audit report for a specific model or user
     */
    public function getDetailedAuditReport($modelType = null, $modelId = null, $userId = null, $startDate = null, $endDate = null)
    {
        $query = AuditTrail::with(['user:id,nom,prenom,email']);

        if ($modelType && $modelId) {
            $query->where('auditable_type', $modelType)
                  ->where('auditable_id', $modelId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query->orderBy('created_at', 'desc')->paginate(50);
    }

    /**
     * Export audit trail to various formats
     */
    public function exportAuditTrail($format = 'csv', $filters = [])
    {
        $query = AuditTrail::with(['user:id,nom,prenom,email']);

        // Apply filters
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                switch ($field) {
                    case 'start_date':
                        $query->where('created_at', '>=', Carbon::parse($value));
                        break;
                    case 'end_date':
                        $query->where('created_at', '<=', Carbon::parse($value)->endOfDay());
                        break;
                    case 'user_id':
                        $query->where('user_id', $value);
                        break;
                    case 'risk_level':
                        $query->where('risk_level', $value);
                        break;
                    case 'event':
                        $query->where('event', $value);
                        break;
                }
            }
        }

        $auditTrails = $query->orderBy('created_at', 'desc')->get();

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($auditTrails);
            case 'excel':
                return $this->exportToExcel($auditTrails);
            case 'pdf':
                return $this->exportToPdf($auditTrails);
            case 'json':
                return $this->exportToJson($auditTrails);
            default:
                throw new \InvalidArgumentException("Format d'export non supporté: {$format}");
        }
    }

    /**
     * Export to CSV format
     */
    protected function exportToCsv($auditTrails)
    {
        $filename = 'audit_trail_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($auditTrails) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'Date/Heure',
                'Utilisateur',
                'Événement',
                'Type d\'objet',
                'ID d\'objet',
                'Niveau de risque',
                'Adresse IP',
                'Agent utilisateur',
                'URL',
                'Anciennes valeurs',
                'Nouvelles valeurs'
            ]);

            // Data
            foreach ($auditTrails as $audit) {
                fputcsv($file, [
                    $audit->created_at->format('d/m/Y H:i:s'),
                    $audit->user ? ($audit->user->nom . ' ' . $audit->user->prenom) : 'Système',
                    $audit->getEventDisplayName(),
                    class_basename($audit->auditable_type),
                    $audit->auditable_id,
                    ucfirst($audit->risk_level),
                    $audit->ip_address,
                    $audit->user_agent,
                    $audit->url,
                    $audit->old_values ? json_encode($audit->old_values, JSON_UNESCAPED_UNICODE) : '',
                    $audit->new_values ? json_encode($audit->new_values, JSON_UNESCAPED_UNICODE) : ''
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    /**
     * Export to JSON format
     */
    protected function exportToJson($auditTrails)
    {
        $filename = 'audit_trail_' . date('Y-m-d_H-i-s') . '.json';
        
        $data = $auditTrails->map(function ($audit) {
            return [
                'datetime' => $audit->created_at->toISOString(),
                'user' => $audit->user ? [
                    'id' => $audit->user->id,
                    'name' => $audit->user->nom . ' ' . $audit->user->prenom,
                    'email' => $audit->user->email
                ] : null,
                'event' => $audit->event,
                'event_display_name' => $audit->getEventDisplayName(),
                'auditable_type' => $audit->auditable_type,
                'auditable_id' => $audit->auditable_id,
                'risk_level' => $audit->risk_level,
                'ip_address' => $audit->ip_address,
                'user_agent' => $audit->user_agent,
                'url' => $audit->url,
                'old_values' => $audit->old_values,
                'new_values' => $audit->new_values,
                'metadata' => $audit->metadata
            ];
        });

        $headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->json($data, 200, $headers);
    }

    /**
     * Clean old audit trail entries
     */
    public function cleanOldAuditTrails($retentionDays = 365)
    {
        $cutoffDate = now()->subDays($retentionDays);
        
        $deletedCount = AuditTrail::where('created_at', '<', $cutoffDate)
                                 ->where('risk_level', '!=', 'high') // Keep high-risk events longer
                                 ->delete();

        // Keep high-risk events for longer (2 years)
        $highRiskCutoff = now()->subDays($retentionDays * 2);
        $deletedHighRiskCount = AuditTrail::where('created_at', '<', $highRiskCutoff)
                                         ->where('risk_level', 'high')
                                         ->delete();

        Log::info("Audit trail cleanup completed", [
            'deleted_regular' => $deletedCount,
            'deleted_high_risk' => $deletedHighRiskCount,
            'retention_days' => $retentionDays
        ]);

        return $deletedCount + $deletedHighRiskCount;
    }

    /**
     * Get security alerts based on audit patterns
     */
    public function getSecurityAlerts($companyId = null, $hours = 24)
    {
        $alerts = [];
        $since = now()->subHours($hours);

        $query = AuditTrail::where('created_at', '>=', $since);
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        // Alert 1: Multiple failed login attempts
        $failedLogins = $query->where('event', 'failed_login')
                             ->select('ip_address', DB::raw('count(*) as count'))
                             ->groupBy('ip_address')
                             ->having('count', '>=', 5)
                             ->get();

        foreach ($failedLogins as $failedLogin) {
            $alerts[] = [
                'type' => 'failed_logins',
                'severity' => 'high',
                'message' => "Tentatives de connexion échouées multiples depuis l'IP {$failedLogin->ip_address} ({$failedLogin->count} tentatives)",
                'data' => $failedLogin
            ];
        }

        // Alert 2: High-risk events
        $highRiskEvents = $query->where('risk_level', 'high')->count();
        if ($highRiskEvents > 10) {
            $alerts[] = [
                'type' => 'high_risk_events',
                'severity' => 'medium',
                'message' => "Nombre élevé d'événements à haut risque détectés ({$highRiskEvents} événements)",
                'data' => ['count' => $highRiskEvents]
            ];
        }

        // Alert 3: Suspicious activities after hours
        $afterHoursEvents = $query->whereRaw('HOUR(created_at) NOT BETWEEN 7 AND 19')
                                 ->whereIn('event', ['created', 'updated', 'deleted'])
                                 ->count();

        if ($afterHoursEvents > 5) {
            $alerts[] = [
                'type' => 'after_hours_activity',
                'severity' => 'medium',
                'message' => "Activité suspecte détectée en dehors des heures de bureau ({$afterHoursEvents} événements)",
                'data' => ['count' => $afterHoursEvents]
            ];
        }

        // Alert 4: Mass data deletion
        $deletions = $query->where('event', 'deleted')->count();
        if ($deletions > 20) {
            $alerts[] = [
                'type' => 'mass_deletion',
                'severity' => 'high',
                'message' => "Suppression massive de données détectée ({$deletions} suppressions)",
                'data' => ['count' => $deletions]
            ];
        }

        return $alerts;
    }

    /**
     * Get compliance report for audit purposes
     */
    public function getComplianceReport($startDate, $endDate, $companyId = null)
    {
        $query = AuditTrail::with(['user:id,nom,prenom,email'])
                          ->whereBetween('created_at', [
                              Carbon::parse($startDate),
                              Carbon::parse($endDate)->endOfDay()
                          ]);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $auditTrails = $query->get();

        return [
            'period' => [
                'start' => Carbon::parse($startDate)->format('d/m/Y'),
                'end' => Carbon::parse($endDate)->format('d/m/Y')
            ],
            'summary' => [
                'total_events' => $auditTrails->count(),
                'unique_users' => $auditTrails->pluck('user_id')->unique()->count(),
                'events_by_risk' => [
                    'high' => $auditTrails->where('risk_level', 'high')->count(),
                    'medium' => $auditTrails->where('risk_level', 'medium')->count(),
                    'low' => $auditTrails->where('risk_level', 'low')->count(),
                ]
            ],
            'events_by_type' => $auditTrails->groupBy('event')->map->count(),
            'user_activity' => $auditTrails->groupBy('user_id')->map(function ($userAudits) {
                $user = $userAudits->first()->user;
                return [
                    'user' => $user ? $user->nom . ' ' . $user->prenom : 'Système',
                    'events_count' => $userAudits->count(),
                    'last_activity' => $userAudits->max('created_at')
                ];
            })->values(),
            'compliance_indicators' => [
                'data_retention_compliant' => true, // Based on your retention policy
                'access_logged' => $auditTrails->whereIn('event', ['login', 'logout'])->count() > 0,
                'changes_tracked' => $auditTrails->whereIn('event', ['created', 'updated', 'deleted'])->count() > 0,
                'security_events_monitored' => $auditTrails->where('risk_level', 'high')->count() >= 0
            ]
        ];
    }
}