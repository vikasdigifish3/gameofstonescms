<?php

namespace App\Models;
use App\Models\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];
    // Models/Portal.php

 
    public function languages()
    {
        return $this->hasMany(Language::class);
    }

     public function categories()
    {
        return $this->belongsToMany(Category::class, 'portal_category');
    }
     public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_portal');
    }
}
