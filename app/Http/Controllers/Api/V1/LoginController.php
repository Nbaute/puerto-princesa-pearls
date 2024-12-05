<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Request\CreateUser;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(UserLoginRequest $request)
    {
        $attempt = $request->getAttempt();
        if (Auth::attempt($attempt)) {
            /**
             * @var User $user
             */
            $user = Auth::user();
            if (!empty($request->role)) {
                if (!$user->hasRole($request->role)) {
                    return $this->jsonError(message: 'User doesn\'t have the necessary role!', code: Response::HTTP_UNAUTHORIZED);
                }
            }
            if (count($user->roles) === 0) {
                $user->syncRoles('client');
            }

            $firebase = new FirebaseService();
            $fb_token = $firebase->generateFirebaseToken($user->id);
            return $this->jsonSuccess('Login successful', [
                'user' => new UserResource($user),
                'fb_token' => $fb_token,
                'token' => $user->createToken('login')->plainTextToken,
            ]);
        }

        return $this->jsonError(message: 'Email/Phone and/or password is incorrect', code: Response::HTTP_UNAUTHORIZED);
    }
}
