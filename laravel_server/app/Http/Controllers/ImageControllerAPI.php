<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\Image as ImageResource;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as ImageMaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ImageControllerAPI extends Controller
{

    public function store(Request $request)
    {
         try {

            $imageData = $request->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            ImageMaker::make($request->get('image'))->save(public_path($request->get('publicPath')).$fileName);

            return response()->json($fileName, 201);

        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getCardBackPaths(Request $request)
    {
        $files = Storage::files('public/img/cardBacks/');
        return response()->json($files, 200);

    }
}
