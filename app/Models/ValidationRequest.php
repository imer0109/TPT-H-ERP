<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidationRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workflow_id',
        'entity_type',
        'entity_id',
        'company_id',
        'requested_by',
        'current_step',
        'status',
        'reason',
        'data_snapshot',
        'validation_notes',
        'completed_at',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'data_snapshot' => 'json',
        'validation_notes' => 'json',
        'completed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the workflow this request belongs to
     */
    public function workflow()
    {
        return $this->belongsTo(ValidationWorkflow::class, 'workflow_id');
    }

    /**
     * Get the company this request belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who requested validation
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the entity being validated
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * Get all validation steps for this request
     */
    public function validationSteps()
    {
        return $this->hasMany(ValidationStep::class, 'request_id');
    }

    /**
     * Get current validation step
     */
    public function getCurrentStep()
    {
        return $this->validationSteps()
                    ->where('step_number', $this->current_step)
                    ->first();
    }

    /**
     * Get next validator based on current step
     */
    public function getNextValidator()
    {
        $workflowSteps = $this->workflow->steps;
        
        if (isset($workflowSteps[$this->current_step])) {
            $stepConfig = $workflowSteps[$this->current_step];
            
            // Find users with the required role
            $role = Role::where('slug', $stepConfig['role'])
                       ->where('company_id', $this->company_id)
                       ->first();
            
            if ($role) {
                return $role->users()->active()->first();
            }
        }

        return null;
    }

    /**
     * Approve current step and move to next
     */
    public function approve($validatorId, $notes = null)
    {
        // Create validation step record
        ValidationStep::create([
            'request_id' => $this->id,
            'step_number' => $this->current_step,
            'validator_id' => $validatorId,
            'action' => 'approved',
            'notes' => $notes,
            'validated_at' => now()
        ]);

        // Check if this is the last step
        if ($this->workflow->isComplete($this->current_step)) {
            $this->update([
                'status' => 'approved',
                'completed_at' => now()
            ]);

            // Execute the approved action on the entity
            $this->executeApprovedAction();
        } else {
            // Move to next step
            $this->update([
                'current_step' => $this->current_step + 1,
                'status' => 'pending'
            ]);

            // Notify next validator
            $this->notifyNextValidator();
        }

        // Log the approval
        AuditTrail::logEvent('validation_approved', $this, null, null, [
            'validator_id' => $validatorId,
            'step' => $this->current_step,
            'notes' => $notes
        ]);

        return true;
    }

    /**
     * Reject the validation request
     */
    public function reject($validatorId, $reason)
    {
        // Create validation step record
        ValidationStep::create([
            'request_id' => $this->id,
            'step_number' => $this->current_step,
            'validator_id' => $validatorId,
            'action' => 'rejected',
            'notes' => $reason,
            'validated_at' => now()
        ]);

        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason
        ]);

        // Notify requester of rejection
        $this->notifyRequester('rejected');

        // Log the rejection
        AuditTrail::logEvent('validation_rejected', $this, null, null, [
            'validator_id' => $validatorId,
            'step' => $this->current_step,
            'reason' => $reason
        ]);

        return true;
    }

    /**
     * Execute the approved action on the entity
     */
    protected function executeApprovedAction()
    {
        try {
            $entity = $this->entity;
            
            if ($entity) {
                // Mark entity as validated
                if (method_exists($entity, 'markAsValidated')) {
                    $entity->markAsValidated();
                }

                // Update entity status if it has one
                if ($entity->hasAttribute('status')) {
                    $entity->update(['status' => 'validated']);
                }
                
                // For companies and agencies, we need to activate them
                if ($entity instanceof \App\Models\Company) {
                    $entity->update(['active' => true]);
                } elseif ($entity instanceof \App\Models\Agency) {
                    $entity->update(['statut' => 'active']);
                }
            }

            // Notify requester of approval
            $this->notifyRequester('approved');

        } catch (\Exception $e) {
            \Log::error('Failed to execute approved action for validation request: ' . $e->getMessage(), [
                'request_id' => $this->id,
                'entity_type' => $this->entity_type,
                'entity_id' => $this->entity_id
            ]);
        }
    }

    /**
     * Notify next validator
     */
    protected function notifyNextValidator()
    {
        $nextValidator = $this->getNextValidator();
        
        if ($nextValidator) {
            // Send notification (implement based on your notification system)
            // This could be email, SMS, in-app notification, etc.
        }
    }

    /**
     * Notify requester of status change
     */
    protected function notifyRequester($status)
    {
        // Send notification to requester (implement based on your notification system)
    }

    /**
     * Check if request is expired
     */
    public function isExpired()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $workflowSteps = $this->workflow->steps;
        $currentStepConfig = $workflowSteps[$this->current_step] ?? null;
        
        if ($currentStepConfig && isset($currentStepConfig['timeout_hours'])) {
            $timeoutHours = $currentStepConfig['timeout_hours'];
            $createdAt = $this->created_at;
            
            // For subsequent steps, use the last validation step timestamp
            $lastStep = $this->validationSteps()
                            ->where('step_number', '<', $this->current_step)
                            ->orderBy('validated_at', 'desc')
                            ->first();
            
            if ($lastStep) {
                $createdAt = $lastStep->validated_at;
            }

            return $createdAt->addHours($timeoutHours)->isPast();
        }

        return false;
    }

    /**
     * Get validation progress percentage
     */
    public function getProgressPercentage()
    {
        $totalSteps = count($this->workflow->steps);
        $completedSteps = $this->validationSteps()->where('action', 'approved')->count();
        
        return ($completedSteps / $totalSteps) * 100;
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for expired requests
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
                    ->whereHas('workflow', function ($q) {
                        // Complex query to check for expired requests
                        // This would need to be implemented based on the timeout logic
                    });
    }

    /**
     * Scope for specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope for specific validator
     */
    public function scopeForValidator($query, $validatorId)
    {
        return $query->whereHas('workflow', function ($q) use ($validatorId) {
            // Find requests where current step requires this validator
        });
    }
}