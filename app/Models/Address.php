<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street_address',
        'street_number',
        'unit_number',
        'postal_code',
        'latitude',
        'longitude',
        'additional_info',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function buildings(): MorphToMany
    {
        return $this->morphedByMany(Building::class, 'addressable');           
    }

    public function accounts(): MorphToMany
    {
        return $this->morphedByMany(Account::class, 'addressable');
    }

    public function formattedAddress(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn ($value) => $this->street_address.' '.$this->street_number.', '.$this->postal_code
        );
    }

    
}
