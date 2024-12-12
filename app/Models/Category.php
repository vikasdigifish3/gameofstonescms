<?php

namespace App\Models;
use App\Models\Content;
use App\Models\CategoryLanguage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'parent_category'
    ];

    public function contents()
    {
        return $this->hasMany(Content::class, 'category_id');
    }
    public function translations()
    {
        return $this->hasMany(CategoryLanguage::class, 'category_id');
    }
    public function portal()
    {
        return $this->belongsTo(Portal::class, 'portal_id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category');
    }

    public function portals()
    {
     return $this->belongsToMany(Portal::class, 'portal_category');
    }

}
