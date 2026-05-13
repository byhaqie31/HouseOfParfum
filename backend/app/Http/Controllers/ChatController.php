<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::get('role') === '1'){
            $chats = DB::table('chat')
                    ->orderBy('created_at', 'desc')
                    ->get();
            // $chats = Chat::distinct()->orderBy('created_at')->get();
            return view('chat-list')->with(['chats'=>$chats->unique('sender')]);
        }

        else{
            $chats = Chat::all()->sortBy('created_at');
            return view ('chat')->with(['chats'=>$chats]);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $users = User::where('role', '1')->get();
        $chat = new Chat();
        $chat->role = Session::get('role');
        $chat->sender = auth()->user()->email;

        if (Session::get('role') === '0')
            foreach ($users as $user)
            $chat->receiver =$user->email;
        else
            $chat->receiver =$request->input('receiver');

        $chat->text = $request->input('text');
        $chat->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chats = Chat::where('sender', $id)->orWhere('receiver', $id)->orderBy('created_at')->get();
        return view ('chat')->with(['chats'=>$chats, 'sender'=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notif = Chat::where('sender', $id)->orderBy('created_at', 'desc')->first()->sender;
        return $notif;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
