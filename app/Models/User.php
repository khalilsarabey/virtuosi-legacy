<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasLocalePreference, HasMedia
{
    use HasFactory;
    use HasAdvancedFilter;
    use Notifiable;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasApiTokens;

    public const TYPE_SELECT = [
        'agency' => 'Agency',
        'user'   => 'Team Member',
        'accounting'   => 'Accountant',
        'admin'  => 'Admin',
        'api'    => 'API User',
        'white-admin'  => 'White Label Admin',
    ];

    public $table = 'users';

    public $orderable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'type',
        'restricted',
        'balance',
        'is_approved',
        'active',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'type',
        'restricted',
        'balance',
        'is_approved',
        'active',
    ];

    protected $appends = [
        'logo',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'restricted',
        'balance',
        'pricing_class_id',
        'is_approved',
        'active',
        'auto_pricing'
    ];

    public function preferredLocale()
    {
        return $this->locale;
    }


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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function custom_pricings()
    {
        return $this->hasMany(Pricing::class);
    }

    public function pricing_class()
    {
        return $this->belongsTo(PricingClass::class);
    }

    public function getTypeLabelAttribute($value)
    {
        return static::TYPE_SELECT[$this->type] ?? null;
    }

    public function getLogoAttribute()
    {
        return $this->getMedia('user_logo')->map(function ($item) {
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

    public function getNextId()
    {
        $statement = DB::select("show table status like 'users'");
        return $statement[0]->Auto_increment;
    }
}
