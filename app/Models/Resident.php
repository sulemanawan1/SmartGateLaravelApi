<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{


    use HasFactory;

protected $primarykey='residentid';

    protected $fillable = [
        "residentid",
        "subadminid",
        "country",
        "state",
        "city",
        "societyname",
        "phasename",
        "blockname",
        "streetname",
        "houseid",
        "houseaddress",
        "vechileno",
        "residenttype",
        "propertytype",
        "committeemember",
        "status",
    ];


    public function society()
    {
        return $this->hasMany('App\Models\Society',"id",'societyid');
    }

    public function phase()
    {
        return $this->hasMany('App\Models\Phase',"id",'pid');
    }
    
    public function block()
    {
        return $this->hasMany('App\Models\Block',"id",'bid');
    }
    
    public function street()
    {
        return $this->hasMany('App\Models\Street',"id",'sid');
    }
    
    public function property()
    {
        return $this->hasMany('App\Models\Property',"id",'propertyid');
    }
    
    public function measurement()
    {
        return $this->hasMany('App\Models\Measurement',"id",'measurementid');
    }
    
    public function owner()
    {
        return $this->hasMany('App\Models\Owner',"residentid",'residentid');
    }


    public function building()
    {

        return $this->hasMany('App\Models\Societybuilding',"id",'buildingid');
    }

    public function floor()
    {


        return $this->hasMany('App\Models\Societybuildingfloor',"id",'societybuildingfloorid');
    }



    public function apartment()
    {

        return $this->hasMany('App\Models\Societybuildingapartment',"id",'societybuildingapartmentid');

    }

  
    

    protected $casts = [ "status"=> 'integer',


   ];
}
