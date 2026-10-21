<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Agency extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'array',
        'economy_classes' => 'array',
        'business_classes' => 'array',
    ];

    protected $appends = [
        'logo',
        'login_bg',
        'search_bg',
        'print_logo'
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

    public function getLogoAttribute()
    {
        return $this->getMedia('agency_logo')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['thumbnail']         = config('app.image_url') . $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = config('app.image_url') . $item->getUrl('preview_thumbnail');
            return $media;
        });
    }

    public function getLoginBgAttribute()
    {
        return $this->getMedia('agency_login_bg')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['thumbnail']         = config('app.image_url') . $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = config('app.image_url') . $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function getPrintLogoAttribute()
    {
        return $this->getMedia('agency_print_logo')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['thumbnail']         = config('app.image_url') . $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = config('app.image_url') . $item->getUrl('preview_thumbnail');

            return $media;
        });
    }


    public function getSearchBgAttribute()
    {
        return $this->getMedia('agency_search_bg')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['thumbnail']         = config('app.image_url') . $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = config('app.image_url') . $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNextId()
    {
        $statement = DB::select("show table status like 'agencies'");
        return $statement[0]->Auto_increment;
    }

}
