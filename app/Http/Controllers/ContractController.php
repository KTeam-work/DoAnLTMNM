<?php

namespace App\Http\Controllers;


class ContractController extends Controller
{
    public function index()
    {
        return view('tenant.contracts.index');
    }

   public function show($id)
    {
       
        

       
        return view('tenant.contracts.show', compact('id'));
    }
  
}
