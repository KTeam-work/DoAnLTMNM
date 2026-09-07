<?php

namespace App\Http\Controllers;


class ContractController extends Controller
{
    public function index()
    {
        return view('tenant.contracts.index');
    }

    public function show(){
        return view('tenant.contracts.show');
    }
  
}
