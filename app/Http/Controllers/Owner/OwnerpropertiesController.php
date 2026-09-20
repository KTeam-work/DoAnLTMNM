<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;


class OwnerpropertiesController extends Controller{
    public function index(){
      
       return view("owner.properties.index");

    }
}