<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;


class OwnerContractController extends Controller{
      public function index()
    {
        return view('owner.contracts.index');
    }

    public function create(){
        return view('owner.contracts.create');
    }

   public function show($id)
    {
       
        

       
        return view('owner.contracts.show', compact('id'));
    }
}