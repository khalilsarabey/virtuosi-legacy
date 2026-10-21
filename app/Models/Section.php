<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Section extends Model implements HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use InteractsWithMedia;

    public const TYPE_SELECT = [
        'images'  => 'Images',
        'linked_images'  => 'External Linked Images',
    ];

    public $table = 'sections';

    public $filterable = [
        'id',
        'name',
        'type',
    ];

    public $orderable = [
        'id',
        'name',
        'type',
        'active',
        'position',
    ];

    protected $casts = [
        'active' => 'boolean',
        'permissions' => 'array',
        'agencies_permissions' => 'array',
        'whitelabel_permissions' => 'array'
    ];

    protected $appends = [
        'image',
        'banner',
    ];

    protected $fillable = [
        'name',
        'type',
        'active',
        'position',
        'permissions',
        'agencies_permissions',
        'whitelabel_permissions'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $thumbnailWidth  = 50;
        $thumbnailHeight = 50;

        $thumbnailPreviewWidth  = 120;
        $thumbnailPreviewHeight = 120;

        $this->addMediaConversion('thumbnail')
            ->width($thumbnailWidth)
            ->height($thumbnailHeight)
            ->fit('crop', $thumbnailWidth, $thumbnailHeight);
        $this->addMediaConversion('preview_thumbnail')
            ->width($thumbnailPreviewWidth)
            ->height($thumbnailPreviewHeight)
            ->fit('crop', $thumbnailPreviewWidth, $thumbnailPreviewHeight);
    }

    public function getImageAttribute()
    {
        return $this->getMedia('section_image')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();
            $media['thumbnail'] = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getBannerAttribute()
    {
        return $this->getMedia('section_banner')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();
            $media['thumbnail'] = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function getTypeLabelAttribute($value)
    {
        return static::TYPE_SELECT[$this->type] ?? null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeRestrict($query)
    {
        $query->where(function ($q) {
        $q
            ->where('whitelabel_permissions', 'NOT LIKE', '%"' . auth()->user()->agency_id . '"%')
            ->orWhere('whitelabel_permissions', '')
            ->orWhereNull('whitelabel_permissions');
        })->where(function ($q) {
            $q
                ->where('agencies_permissions', 'NOT LIKE', '%' . auth()->user()->email . '%')
                ->orWhere('agencies_permissions', '')
                ->orWhereNull('agencies_permissions');
        })->where('active', 1);
    }
}
