<?php

namespace App\Models;

use App\Models\Content;
use App\Models\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageContent extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'language_contents';

    protected $fillable = [
        'content_id',
        'language_id',
        'name',
        'short_title',
        'description',
        'thumb_url',
        'banner_url',
        'file_path',
        'remote_file_path',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
