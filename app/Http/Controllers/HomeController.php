<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
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

    public function dashboard()
    {
        return view('dashboard');
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // photo
        $photo = $request->file('photo');
        $filename = date('Y-m-d').$photo->getClientOriginalName();
        $path = 'photo-user/'.$filename;

        Storage::disk('public')->put($path, file_get_contents($photo));

        $data['email'] = $request->email;
        $data['name'] = $request->name;
        $data['password'] = bcrypt($request->password);
        $data['image'] = $filename;

        User::create($data);

        return redirect()->route('admin.index');
    }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);

        return view('edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['email'] = $request->email;
        $data['name'] = $request->name;

        if($request->password){

            $data['password'] = bcrypt($request->password);
        }

        User::whereId($id)->update($data);

        return redirect()->route('admin.index');
    }

    public function delete(Request $request, $id)
    {
        $data = User::find($id);

        if($data)
        {
            $data->delete();
        }

        return redirect()->route('admin.index');
    }
}
