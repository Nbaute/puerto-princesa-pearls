<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->phone_verified_at = now();
            $user->password = Hash::make($request->password);
            $user->save();

            $user->addMedia($request->image)->toMediaCollection('image');

            if ($request->role === 'driver') {
                $vehicle = new Vehicle();
                $vehicle->lat = $request->lat;
                $vehicle->lng = $request->lng;
                $vehicle->brand = $request->brand;
                $vehicle->v_model = $request->v_model;
                $vehicle->color = $request->color;
                $vehicle->plate_no = $request->plate_no;
                $vehicle->description = $request->description;
                $vehicle->seat_formation_code = $request->seat_formation_code;
                $vehicle->driver_id = $user->id;
                $vehicle->vehicle_type_id = $request->vehicle_type_id;
                $vehicle->save();
            }

            DB::commit();

            $firebase = new FirebaseService();
            $fb_token = $firebase->generateFirebaseToken($user->id);
            return $this->jsonSuccess('Registration successful', [
                'user' => new UserResource($user),
                'fb_token' => $fb_token,
                'token' => $user->createToken('login')->plainTextToken,
            ]);
        } catch (Throwable $t) {
            DB::rollBack();
            return $this->jsonError("An error occurred! " . $t->getMessage());
        }

    }

    public function editProfile(EditProfileRequest $request){
        /**
         * @var User $user
         */
        $user = Auth::user();
        $user->clearMediaCollection('image');
        $user->addMedia($request->image)->toMediaCollection('image');
        return $this->jsonSuccess('Successful!', new UserResource($user));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::query()->where('phone', $request->phone)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->jsonSuccess("Password updated!");
    }
    public function changePassword(Request $request)
    {
        $user = $request->user();
        $attempt = Hash::check($request->password, $user->password);
        if (!$attempt) {
            return $this->jsonError("Password is incorrect!");
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->jsonSuccess("Password updated!");
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        $user = User::query()->where('phone', $request->phone)->first();
        $user->deletion_reason = $request->reason;
        $user->save();
        $user->delete();

        return $this->jsonSuccess("Account deletion successful");
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        return $this->jsonSuccess('Fetched', [
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = User::find(Auth::guard('api')->user()->id);
            if (!empty($user)) {
                if ($user->hasAnyRole('driver')) {
                    $user->is_online = false;
                    $user->save();
                }
                $bearerToken = $request->bearerToken();
                $token = PersonalAccessToken::findToken($bearerToken);
                if (!empty($token)) {
                    $token->delete();
                }
                return $this->jsonSuccess("Logout successful", [
                    'user' => $user,
                ]);
            } else {
                return $this->jsonError("Logout failed");
            }
        } catch (Throwable $e) {
            return $this->jsonSuccess("Logout successful!");
        }
    }

}
