<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataTableController extends Controller
{
    public function clientside(Request $request)
    {
        // pengambilan data
        $data = User::orderBy('created_at', 'desc')->get();

        // pencarian
        if($request->get('search'))
        {
            $data = $data->filter(function ($user) use ($request) {
                return  stripos($user->name, $request->get('search')) !== false ||
                        stripos($user->email, $request->get('search')) !== false ;
            });

        }

        return view('datatable.clientside', compact('data' , 'request'));
    }

 
    public function serverside(Request $request)
    {
        // pengambilan data
        $data = User::orderBy('created_at', 'desc')->get();

        // pencarian
        if($request->get('search'))
        {
            $data = $data->filter(function ($user) use ($request) {
                return  stripos($user->name, $request->get('search')) !== false ||
                        stripos($user->email, $request->get('search')) !== false ;
            });

        }

        return view('index', compact('data' , 'request'));
    }

   
}
