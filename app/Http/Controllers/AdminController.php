<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function adminItems()
    {
        return response()->json(Item::orderBy('id', 'asc')->get());
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $user->createToken($request->email)->plainTextToken;
    }

    public function chat(Request $request)
    {
        $message = new message();
        $message->sender_id = $request->sender_id;
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message;
        $message->save();
    }

    public function listMessage()
    {
        return response()->json(message::orderBy('id', 'asc')->get());
    }
}