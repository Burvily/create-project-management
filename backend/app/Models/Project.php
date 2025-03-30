<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'progress',
        'start_date',
        'due_date',
        'budget_total',
        'budget_spent',
        'risk_level',
        'project_type',
        'priority',
        'manager_id',
        'client',
        'department'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'budget_total' => 'decimal:2',
        'budget_spent' => 'decimal:2',
        'progress' => 'integer',
    ];

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function getBudgetRemainingAttribute()
    {
        return $this->budget_total - $this->budget_spent;
    }

    public function getBudgetPercentageAttribute()
    {
        if ($this->budget_total == 0) return 0;
        return ($this->budget_spent / $this->budget_total) * 100;
    }
}