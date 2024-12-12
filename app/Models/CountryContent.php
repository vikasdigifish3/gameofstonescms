<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryContent extends Model
{
    use HasFactory;
    protected $table = 'country_content';
    
    protected $fillable = [
        'country_id',
        'content_id',
    ];
}
