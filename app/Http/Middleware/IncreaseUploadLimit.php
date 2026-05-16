<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Увеличиваем лимиты для загрузки файлов
        ini_set('upload_max_filesize', '10M');
        ini_set('post_max_size', '12M');
        ini_set('memory_limit', '256M');
        ini_set('max_file_uploads', '20');

        return $next($request);
    }
}
