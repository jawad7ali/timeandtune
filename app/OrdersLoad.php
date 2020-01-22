<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersLoad extends Model
{
	protected $fillable = [
        'load_id', 'user_id', 'shipper_offer','mybidoffer','commission','total'
    ];
    //
}
