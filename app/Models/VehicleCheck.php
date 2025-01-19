<?php

namespace App\Models;

use Spatie\Tags\Tag;
use Spatie\Tags\HasTags;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VehicleCheck extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags;

    const CHECK_RESULTS = [
        'pass' => 'Pass',
        'fail' => 'Fail',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'check_date',
        'check_type',
        'check_result',
        'asset_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'check_date' => 'date',
        'vehicle_id' => 'integer',
        'asset_id' => 'integer',
    ];

    public $localdata;

    protected static function boot() {
        parent::boot();
        $localdata = self::getKteoTags();
    }

    public static function getKteoTags() : string {
        return 'in:' . implode(',',Tag::where('type', 'kteo_tags')->pluck('id')->toArray());
    }


    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

}
