<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function __invoke(Request $request, string $path)
    {
        $relativePath = ltrim($path, '/');
        $disk = Storage::disk('private');

        if (! $disk->exists($relativePath)) {
            abort(404);
        }

        $fullPath = $disk->path($relativePath);

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . basename($relativePath) . '"',
        ]);
    }
}

