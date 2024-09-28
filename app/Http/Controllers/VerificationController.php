<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return new JsonResponse([
                'status' => true,
                'message' => 'Email already verified'
            ], 200);
        }else {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return new JsonResponse([
                'status' => true,
                'message' => 'Email has been verified'
            ], 204);
        }

        // if ($user->markEmailAsVerified()) {
        //     event(new Verified($user));
        // }

        // return new JsonResponse([
        //     'status' => true,
        //     'message' => 'Email has been verified'
        // ], 200);
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return new JsonResponse([
            'status'=> true,
            'message' => 'Verification email resent'
        ], 200);
    }
}
