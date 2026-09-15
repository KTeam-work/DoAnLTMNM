<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;


class OwnerReivewController extends Controller{
    public function index(){
      
       return view("owner.reviews.index");

    }
}