<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class OwnerApptionmentController extends Controller{
    public function index(){

         return view("owner.appointments.index");

    }
}