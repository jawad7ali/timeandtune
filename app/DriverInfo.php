<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverInfo extends Model
{

	 
	protected $fillable = [
        'user_id','address', 'truck_photo_no_plate', 'truck_type','truck_capacity','NIC_front','NIC_back','licence', 'Truck_registration','photo'
    ];

    //
}
