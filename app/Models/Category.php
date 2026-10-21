<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasAdvancedFilter;
    use InteractsWithMedia;

    public const TYPE_SELECT = [
        //'external'  => 'External Package',
        //'promo' => 'Promotion',
        'banner' => 'Banner'
    ];

    public $table = 'categories';

    public $orderable = [
        'id',
        'name',
    ];

    public $filterable = [
        'id',
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'permissions' => 'array',
        'hide_title' => 'boolean',
        'whitelabel_permissions' => 'array',
        'agencies_permissions' => 'array',
    ];

    protected $fillable = [
        'name',
        'link',
        'position',
        'show_title',
        'section_id',
        'active',
        'slug',
        'permissions',
        'whitelabel_permissions',
        'agencies_permissions'
    ];
//    public function getRouteKey()
//    {
//        return 'slug';
//    }
    protected $appends = [
        'image',
        'banner',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $thumbnailWidth  = 500;
        $thumbnailHeight = 500;

        $thumbnailPreviewWidth  = 120;
        $thumbnailPreviewHeight = 120;

        $this->addMediaConversion('opt')
            ->width(1100);

        $this->addMediaConversion('sm')
            ->width($thumbnailWidth);

        $this->addMediaConversion('thumbnail')
            ->width($thumbnailWidth)
            ->height($thumbnailHeight);
            //->fit('crop', $thumbnailWidth, $thumbnailHeight);
        $this->addMediaConversion('preview_thumbnail')
            ->width($thumbnailPreviewWidth)
            ->height($thumbnailPreviewHeight)
            ->fit('crop', $thumbnailPreviewWidth, $thumbnailPreviewHeight);
    }

    public function getTypeLabelAttribute($value)
    {
        return static::TYPE_SELECT[$this->type] ?? null;
    }

    public function flights()
    {
        return $this->belongsToMany(Flight::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageAttribute()
    {
        return $this->getMedia('category_image')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();
            $media['thumbnail'] = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function getFileAttribute()
    {
        return $this->getMedia('category_file')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();

            return $media;
        });
    }

    public function getBannerAttribute()
    {
        return $this->getMedia('category_banner')->map(function ($item) {
            $media = $item->toArray();
            $media['url'] = $item->getUrl();
            $media['thumbnail'] = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
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
