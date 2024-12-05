<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show(Request $request)
    {
        $settings = [
            "colors" => [],
        ];

        return $this->jsonSuccess("Success", $settings);
    }
}
