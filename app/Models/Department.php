<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\PreventDeletionException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'parent_department_id',
        'budget',
        'is_active',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'status' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($department) {
            if ($department->childDepartments()->exists()) {
                throw new PreventDeletionException('Backend:This department cannot be deleted because it has child departments.');
                return false;
            }
        });
    }

    public function canBeDeleted(): bool
    {
        return !$this->childDepartments()->exists();
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function parentDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

    public function childDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }
}
