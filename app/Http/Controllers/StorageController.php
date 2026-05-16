<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    /**
     * Serve files from storage/app/public directory
     * Works completely offline - serves files directly from project directory
     */
    public function serve(Request $request, string $path)
    {
        // Безопасность: предотвращаем доступ к файлам вне public директории
        $path = str_replace('..', '', $path);
        $path = ltrim($path, '/');
        
        $filePath = storage_path('app/public/' . $path);
        
        if (!file_exists($filePath) || !is_file($filePath)) {
            abort(404, 'File not found');
        }

        // Определяем MIME-тип для правильной отдачи изображений
        $mimeType = mime_content_type($filePath);
        if (!$mimeType) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        }

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
