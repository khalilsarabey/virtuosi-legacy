<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Block extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'blocks';

    protected $casts = [
        'hide_title' => 'boolean',
        'active'     => 'boolean'
    ];

    protected $appends = [
        'thumbnail'
    ];

    public const LAYOUT_SELECT = [
        '1' => '1 Column',
        '2' => '2 Columns',
        '3' => '3 Columns',
        '4' => '4 Columns',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = ['*'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function children()
    {
        return $this->hasMany(Block::class, 'parent_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $thumbnailWidth  = 650;
        $thumbnailHeight = 650;

        $thumbnailPreviewWidth  = 50;
        $thumbnailPreviewHeight = 50;

        $this->addMediaConversion('thumbnail')
            ->width($thumbnailWidth)
            ->height($thumbnailHeight)
            ->fit('crop', $thumbnailWidth, $thumbnailHeight);
        $this->addMediaConversion('preview_thumbnail')
            ->width($thumbnailPreviewWidth)
            ->height($thumbnailPreviewHeight)
            ->fit('crop', $thumbnailPreviewWidth, $thumbnailPreviewHeight);
    }

    public function getLayoutLabelAttribute($value)
    {
        return static::LAYOUT_SELECT[$this->layout] ?? null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function getThumbnailAttribute()
    {
        return $this->getMedia('block_thumbnail')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = $item->getUrl();
            $media['thumbnail']         = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }


    public function getImageAttribute()
    {
        return $this->getMedia('block_image')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = $item->getUrl();
            $media['thumbnail']         = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }


}
