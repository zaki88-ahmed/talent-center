<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\EndUserInterface;



class EndUserController extends Controller
{


    private $endUserInterface;

    public function __construct(EndUserInterface $endUserInterface)
    {
        $this->endUserInterface = $endUserInterface;
    }


    public function userGroups(){

//        dd('zz');
        return $this->endUserInterface->userGroups();
    }
}
