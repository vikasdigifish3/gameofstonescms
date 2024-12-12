<?php

namespace App\Models;
use App\Models\Language;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'status',
        'language_id',
        'is_featured',
        'thumb_url',
        'thumb_file_id',
        'banner_url',
        'banner_file_id',
        'file_path',
        'file_file_id',
        'remote_file_path',
        'content_type',
        'short_title',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function portal()
    {
        return $this->belongsTo(Portal::class, 'portal_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_content');
    }
     public function portals()
    {
        return $this->belongsToMany(Portal::class, 'content_portal');
    }

    public function translations()
    {
        return $this->hasMany(LanguageContent::class, 'content_id');
    }
}
