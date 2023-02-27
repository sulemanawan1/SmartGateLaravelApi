<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;


    protected $fillable = [

        'residentid',

        "charges",
        	"chargesafterduedate"	,
            "appcharges",
            	"tax",	"balance",
                	"subadminid",	
                    "residentid",	"propertyid",
                    	"measurementid",
                        	"duedate"	,"billstartdate",

                            "billenddate",	"month"	, "status"	
        
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User',"id",'residentid');
    }
}
