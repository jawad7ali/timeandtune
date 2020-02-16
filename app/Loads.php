<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loads extends Model
{

	protected $table ='loads';
   	protected $primaryKey = 'id';
   

	 protected $fillable = [
        'name', 'from', 'to','categories','price','pickup_date','pickup_time', 'distance','model','loadtype','weight','length','width','user_id','role_id', 'status'
    ];


    //
}
