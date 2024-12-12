<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notification_logs';

    protected $fillable = [
        'title', 'body', 'iconUrl', 'subdomain', 'countryId', 'status', 'error', 'createdAt',
    ];
}
