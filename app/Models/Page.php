<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_homepage',
        'meta_title',
        'meta_description',
        'status'
    ];

    protected $casts = [
        'content' => 'array',
        'is_homepage' => 'boolean',
        'status' => 'boolean',
    ];

    public function setIsHomepageAttribute($value)
    {
        if ($value) {
            // If this page is set as homepage, unset all other pages
            self::where('id', '!=', $this->id)->update(['is_homepage' => false]);
        }
        $this->attributes['is_homepage'] = $value;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }
}
