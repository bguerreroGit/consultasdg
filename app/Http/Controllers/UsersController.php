<?php

namespace App\Http\Controllers;
use App\User;
use App\Course;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function lista() {
    	$lista= Course::all()->sortBy('id');

    	return view('tabla', compact('lista'));
    }

     public function delete(Request $request) {
     	//dd($request);
    	$campo= Course::findOrFail($request->id);
    	$campo->delete();
    	return redirect()-> route('lista');
    }
}
