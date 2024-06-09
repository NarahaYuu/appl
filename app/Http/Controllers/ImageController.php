<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        Log::info('Request received', $request->all());

        $request->validate([
            'ip_address' => 'required',
        ]);

        $fullAddress = $request->ip_address;
        $ipParts = explode(':', $fullAddress, 2);
        $ipAddress = $ipParts[0];
        $path = isset($ipParts[1]) ? $ipParts[1] : '';

        if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
            return response()->json(['error' => 'Invalid IP address'], 400);
        }

        // Fetch the image from IP Webcam
        $response = Http::get('http://' . $fullAddress);

        if ($response->successful()) {
            $imageName = time() . '.jpg';
            $imagePath = public_path('images/' . $imageName);
            file_put_contents($imagePath, $response->body());

            Image::create([
                'name' => $imageName,
            ]);

            return response()->json(['success' => 'Image uploaded successfully.']);
        }

        return response()->json(['error' => 'Failed to fetch image from IP Webcam.'], 500);
    }

}

