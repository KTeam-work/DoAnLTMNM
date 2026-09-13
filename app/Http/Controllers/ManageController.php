<?php

namespace App\Http\Controllers;


class ManageController extends Controller
{
    public function manage(){
        return view("owner/tenants/manage");
    }
  
}
