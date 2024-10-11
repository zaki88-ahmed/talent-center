<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Traits\ApiDesignTrait;

class ApiController extends Controller
{
    
    use ApiDesignTrait;

    public function testApi($name)
    {
       
        if($name == 'mohamed'){
            return $this->ApiResponse(200, 'Done', null, $name);
        }
        else{
            return $this->ApiResponse(422, 'Valdation Errors', 'name must be mohamed', null);
        }
    }


}
