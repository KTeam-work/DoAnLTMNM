<?php

namespace App\Http\Controllers;


class OwnerContractController extends Controller
{
    public function index(){
        return view("owner/contracts/index");
    }

      public function create(){
        return view("owner/contracts/create");
    }
  
      public function show(){
        return view("owner/contracts/show");
    }
  
  

}
