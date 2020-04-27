<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create images');

        $request->validate(['image' => 'required|image']);

        $image = Image::make($request->file('image'))->resize(900, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg')->__toString();
        $path = 'public/images/' . md5($image) . '.jpg';
        Storage::put($path, $image);
 
        return response()->json(['url' => asset(Storage::url($path))]);
    }
}
