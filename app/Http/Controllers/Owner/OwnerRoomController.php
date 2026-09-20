<?php


namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;


class OwnerRoomController extends Controller{

   public function index(){
     
      return view("owner.rooms.index");

   }
}