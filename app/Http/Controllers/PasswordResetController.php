<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use Illumitate\Support\Facades\Mail;
// use Illuminate\Contracts\Mail\Mailer;
// use Illumitate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Mail as FacadesMail;

class PasswordResetController extends Controller
{
    public function send_reset_password_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response([
                'message' => 'password chenge success',
                'stasut' => 'success',
            ], 200);
        }

        $token = Str::random(60);

        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset your password');
            $message->to($email);
            
        });
        return response([
            'message' => 'password Reset Email Sent Check your email',
            'stasut' => 'success',
        ], 200);
    }
    public function reset(Request $request,$token){
        $formatted=Carbon::now()->subMinute(1)->toDateTimeString();
        PasswordReset::where('created_at','<=',$formatted)->delete();
        
        
        $request->validate(
            ['password'=>'required|confirmed',
            ]
        );
        $passwordreset=PasswordReset::where('token',$token)->first();
        if(!$passwordreset){
            return response([
                'message' => 'Token is Invalid or Expired',
                'stasut' => 'failed',
            ], 404); 
        }

        $user=User::where('email',$passwordreset->email)->first();
        $user->password=Hash::make($request->password);
        $user->save();
        // PasswordReset::where('email',$user->email)->delete();
        return response([
            'message' => 'password reset seccessfully',
            'stasut' => 'success',
        ], 200);
        
    }
}