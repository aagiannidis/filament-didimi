<?php

namespace App\Models;

use App\Enums\CompanyType;
use App\Enums\IndustryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'vat_number',
        'email',
        'phone',
        'website',
        'type',
        'industry',
        'is_active',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'type' => CompanyType::class,
        'industry' => IndustryType::class,
    ];

    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable')
            ->withPivot('type','is_correspondence');
    }
}
