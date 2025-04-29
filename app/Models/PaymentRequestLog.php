<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequestLog extends Model
{
    protected $fillable = [
        'provider',
        'request_body',
        'response_body',
    ];
}
