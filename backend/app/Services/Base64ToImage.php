<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class Base64ToImage
{
    public function handle(string $base64Image, string $basePath = 'images/'): string
    {
        preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type);
        $data = substr($base64Image, strpos($base64Image, ',') + 1);
        $data = base64_decode($data);
        $type = strtolower($type[1]); // jpg, png, gif
        $fileName = uniqid() . '.' . $type;
        $filePath = $basePath . $fileName;

        // Save the image to the storage
        Storage::disk('public')->put($filePath, $data);

        return $filePath;
    }
}
