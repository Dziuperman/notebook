<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = ['name', 'email', 'phone', 'website', 'created_at', 'updated_at'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} customer";
    }
}
