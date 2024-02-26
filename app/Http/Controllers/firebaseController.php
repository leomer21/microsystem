<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

class firebaseController extends Controller
{
   public function __construct()
   {
       // $this->middleware('auth');
   }

   public function firebaseView()
   {
       return view('firebaseView');
   }

}