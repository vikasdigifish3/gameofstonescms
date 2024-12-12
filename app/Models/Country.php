<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['name', 'continent_id','shortcode'];

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'country_content');
    }
}
