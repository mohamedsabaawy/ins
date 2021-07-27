<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function loginForm()
    {
        return view('frontend.client.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email', 'password']);
//        dd($login);
        if (!Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->with('state', 'please enter correct email and password')->withInput();
        }
        return redirect(route('welcome'))->with('state', 'successful login');
    }

    public function registerForm()
    {
        return view('frontend.client.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:clients',
            'password' => 'required|confirmed',
        ]);
        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $client->follows()->attach($client);
        return redirect(route('client.login.form'))->with('state', 'register success');
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect(route('client.login.form'));
    }

    public function edit(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$request->avatar == null) {
            Storage::delete($client->avatar);
        };
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => ($request->password == null ? $client->password : bcrypt($request->password)),
            'avatar' => ($request->avatar == null ? $client->avatar : $request->avatar->store('avatar', 'public')),
        ]);
        return redirect(route('welcome'))->with('state', 'profile updated');
    }

//    --------- search method------------------//
    public function search(Request $request)
    {
        $clients = Client::where('name', 'LIKE', '%' . $request->search . '%')->take(5)->get();
        $out = '';
        if (count($clients) > 0) {
            foreach ($clients as $client) {
                $out .= '<tr><th><a class="btn" href="' . route('client.profile', $client->id) . '"><img width="30px" height="30px" src="' . ($client->avatar ? asset('storage/' . $client->avatar) : asset(AVATAR)) . '"></a></th><th><a class="btn" href="' . route('client.profile', $client->id) . '"> ' . $client->name . '</a></th</th></tr>';
            }
        }
        return response()->json([
            'result' => $out,
        ]);
    }

//---------------------------------------------------------------//

    public function follow(Request $request)
    {
        ;
        $follow = $request->user('client')->follows()->toggle($request->follow);
        return $follow;
    }

//---------------------------------------------------------------//

    public function profile($id)
    {
        $client = Client::with(['follows', 'followers'])->find($id);
        if (!$client)
        {
            return redirect()->back();
        }
        $posts = Post::where('client_id',$client->id)->latest()->paginate(PAGINATE);
        return view('frontend.client.profile', compact('client', 'posts'));
    }

}
