<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MediaFallbackMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // If it's a 404 and the path starts with /media/
        if ($response->status() === 404 && str_starts_with($request->path(), 'media/')) {
            $path = str_replace('media/', '', $request->path());

            // Determine fallback based on file type
            if (str_contains($path, 'room') || str_contains($path, 'gallery')) {
                return redirect(asset('images/default-room.jpg'));
            } elseif (str_contains($path, 'video')) {
                return redirect(asset('images/video-default.jpg'));
            } elseif (str_contains($path, 'meal') || str_contains($path, 'menu')) {
                return redirect('https://images.unsplash.com/photo-1603894584373-5ac82b2ae398');
            }

            return redirect(asset('images/no-image.png'));
        }

        return $response;
    }
}
