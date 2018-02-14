<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Config as ConfigResource;
use App\Config;

class ConfigControllerAPI extends Controller
{
    public function getPlatformEmail()
    {
        $id = 1;
        $config = Config::findOrFail($id);
        return response()->json(new ConfigResource($config), 201);
    }

    public function updatePlatformEmail(Request $request)
    {
        $request->validate([
            'platform_email' => 'required|email|unique:users,email',
        ]);
        $id = 1;
        $config = Config::findOrFail($id);
        $config->platform_email= $request->platform_email;
        $config->platform_email_properties = json_encode([
            'driver'=>'smtp', 
            'host' =>'smtp.mailtrap.io', 
            'port'=>2525, 
            'username'=>'000e41366b0142',
            'password' => '9a85d9b3f39af3',
            'from'=>[
                'address'=>$request->platform_email,
                'name'=>'Projeto DAD'
            ], 
            'encryption' => 'TLS']);
        $config->save();
        return response()->json(new ConfigResource($config), 201);
    }
}