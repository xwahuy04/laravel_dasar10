<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    
    use Illuminate\Support\Facades\Auth;

    use Illuminate\Support\Facades\Hash; // Import Hash facade
        use App\Models\User; // Import model User

    class LoginController extends Controller
    {
        public function index()
        {
            return view('auth.login');
        }

        public function login_proses(Request $request)
        {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if(Auth::attempt($data))
            {   
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('login')->with('failed', 'Email atau Password Salah');
            }
        }

        public function logout()
        {
            Auth::logout();
            return redirect()->route('login')->with('succes', 'Kamu berhasil logout');
        }

        public function register()
        {
            return view('auth.register');
        }

        public function register_proses(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8'
            ]);
        
            // Simpan data pengguna baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password) // Hash password
            ]);
        
            // Coba login pengguna setelah registrasi
            $loginData = [
                'email' => $request->email,
                'password' => $request->password // Gunakan password plain text
            ];
        
            if (Auth::attempt($loginData)) {
                return redirect()->route('admin.dashboard')->with('success', 'Registrasi dan login berhasil!');
            } else {
                return redirect()->route('login')->with('failed', 'Email atau Password Salah');
            }
        }



    }
