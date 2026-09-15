<?php


namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;


class OwnerRentailController extends Controller{
    public function index(){   
         
    return view("owner.rental-posts.index");

     }
}