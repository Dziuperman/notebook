<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = ['name', 'email', 'phone', 'website'];
}
