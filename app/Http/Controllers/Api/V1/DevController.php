<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dev\AssignRoleRequest;
use App\Http\Requests\Dev\RemoveRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function assignRole(AssignRoleRequest $request)
    {
        /**
         * @var User $user
         */
        $user = User::find($request->user_id);
        $user->assignRole($request->role);
        return $this->jsonSuccess(data: new UserResource($user));
    }
    public function removeRole(RemoveRoleRequest $request)
    {
        /**
         * @var User $user
         */
        $user = User::find($request->user_id);
        $user->removeRole($request->role);
        return $this->jsonSuccess(data: new UserResource($user));
    }
}
