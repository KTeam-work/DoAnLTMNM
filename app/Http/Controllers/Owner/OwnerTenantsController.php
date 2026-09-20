<?php



namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;    


class OwnerTenantsController extends Controller{
    public function manage(){
        return view("owner.tenants.manage");
    }

    public function create(){
        return view("owner.tenants.create");
    }

    
      public function show(){
        return view("owner.tenants.show");
    }


       public function edit(){
        return view("owner.tenants.edit");
    }
}