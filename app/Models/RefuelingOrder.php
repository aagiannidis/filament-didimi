<?php

namespace App\Models;

use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;
use TomatoPHP\FilamentDocs\Traits\InteractsWithDocs;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\States\RefuelingOrderStates\RefuelingOrderState;
use ZeeshanTariq\FilamentAttachmate\Core\InteractsWithAttachments;

class RefuelingOrder extends Model
{
    use HasFactory, InteractsWithDocs;
    use HasStates;
    use InteractsWithAttachments;
    use HasFlags;

    protected const STATES_ACCEPTING_ATTACHMENTS = ['Approved'];
    protected const LOCKING_STATES = ['Archived', 'Closed', 'Denied'];
    protected const ALL_STATES = ['Approved', 'Archived', 'Cancelled', 'Closed', 'Denied', 'Draft', 'Pending Approval', 'Processing', 'Returned'];

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

    public function modelAllowsAttachReceipt(): bool
    {
        if ($this->hasFlag('Receipt Uploaded')) return false;

        return in_array($this->status, static::STATES_ACCEPTING_ATTACHMENTS);
    }

    public function modelAllowsAttachDocuments(): bool
    {
        return in_array($this->status, static::STATES_ACCEPTING_ATTACHMENTS);
    }

    public function isLockedForEdits(): bool
    {
        return in_array($this->status, static::LOCKING_STATES);
    }

    public function modelAllowsVerifyReceipt(): bool
    {
        return $this->hasFlag('Receipt Uploaded');
    }

    public function modelAllowsVerifyDocuments(): bool
    {
        return $this->hasFlag('Documents Uploaded');
    }

    public function secureDocuments(): MorphMany
    {
        return $this->morphMany(SecureDocument::class, 'doc_attachable');
    }
}
