<?php

    namespace App\Http\Controllers\Auth;
    use App\User;
    use App\Models\Admin;
    use App\Writer;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Foundation\Auth\RegistersUsers;
    use Illuminate\Http\Request;
    use Response;

    class RegisterController extends Controller
    {
    
        public function __construct()
        {
            // $this->middleware('guest');
            // $this->middleware('guest:admin');
            // $this->middleware('guest:customer');
            $this->middleware('auth:api', ['except' => ['createAdmin', 'createCustomer']]);
        }

        protected function createAdmin(Request $request)
    {

        $rules = [
            'cat_title' => 'bail|required|unique:categories|string',
            'cat_desc' => 'bail|string',
            'cat_type' => 'bail|numeric|required',
            'cat_image' => 'bail|file',
        ];

        $validator = Validator::make($request->all(), $rules);

       
        $admin = Admin::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return response()->json(['status' => 'admin create success']);
    }

    protected function createCustomer(Request $request)
    {
        $this->validator($request->all())->validate();
        $writer = Writer::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return response()->json(['status' => 'customer create success']);
    }

    
}