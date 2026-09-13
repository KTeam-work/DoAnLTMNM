<?php

namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;

class OwnerUtilitiesController extends Controller{
    public function index(){
       return view("owner.utilities.index");
    }

    public function create(){
        return view("owner.utilities.create");
    }
}