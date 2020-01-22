<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_type extends Model
{
    //
    protected $table ='role_types';
   	protected $primaryKey = 'id';
    
    protected $fillable = [
        'name'    
    ];

    public function roles(){
        return $this->belongsTo('App\User','role_id');
    }
}
