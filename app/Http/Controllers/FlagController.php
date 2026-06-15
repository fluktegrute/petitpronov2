<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FlagController extends Controller
{
    private const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];

    private const MIME_MAP = [
        'svg'  => 'image/svg+xml',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
    ];

    public function __invoke(string $filename): BinaryFileResponse
    {
        $filename = basename($filename);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            abort(404);
        }

        $path = storage_path('app/public/images/flags/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        // SVGs servis en attachment : empêche leur rendu direct en tant que page active (XSS)
        $disposition = $ext === 'svg' ? 'attachment' : 'inline';

        return response()->file($path, [
            'Content-Type'           => self::MIME_MAP[$ext],
            'Content-Disposition'    => "{$disposition}; filename=\"{$filename}\"",
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'public, max-age=604800',
        ]);
    }
}
