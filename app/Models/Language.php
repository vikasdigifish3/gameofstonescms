<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'shortcode',
        'status',
    ];

    public function portal()
    {
        return $this->belongsTo(Portal::class);
    }
}
