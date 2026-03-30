<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (!$this->shouldCompress($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || $content === null || $content === '') {
            return $response;
        }

        $compressed = gzencode($content, 6);
        if ($compressed === false) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Vary', 'Accept-Encoding');
        $response->headers->set('Content-Length', (string) strlen($compressed));

        return $response;
    }

    private function shouldCompress(Request $request, Response $response): bool
    {
        if (!$request->headers->has('Accept-Encoding')) {
            return false;
        }

        if (!str_contains($request->headers->get('Accept-Encoding', ''), 'gzip')) {
            return false;
        }

        if ($response->headers->has('Content-Encoding')) {
            return false;
        }

        $type = $response->headers->get('Content-Type', '');
        if ($type && !str_contains($type, 'application/json') && !str_contains($type, 'text/')) {
            return false;
        }

        return $response->isSuccessful();
    }
}
