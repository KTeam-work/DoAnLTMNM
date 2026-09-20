<?php


namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;


class OwnerServiceController extends Controller{

    public function index(){
        return view("owner.services.index");
    }

     public function edit(){
        return view("owner.services.edit");
    }
}