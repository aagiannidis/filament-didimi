<?php

namespace App\Models;

use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentDocs\Traits\InteractsWithDocs;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\States\RefuelingOrder\RefuelingOrderState;

class RefuelingOrder extends Model
{
    use HasFactory, InteractsWithDocs, HasStates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'address_id',
        'asset_id',
        'start_date',
        'end_date',
        'fuel_type',
        'fuel_qty',
        'state',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'company_id' => 'integer',
        'address_id' => 'integer',
        'asset_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'state' => RefuelingOrderState::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->with('addresses');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
