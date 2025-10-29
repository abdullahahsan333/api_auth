<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class EncryptPayload
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🔹 Decrypt incoming request
        if ($request->isJson()) {
            $content = $request->getContent();
            if (!empty($content)) {
                try {
                    $decrypted = Crypt::decryptString($content);
                    $data = json_decode($decrypted, true);
                    $request->replace($data ?? []);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Invalid encrypted request'], 400);
                }
            }
        }

        // Continue request
        $response = $next($request);

        // 🔹 Encrypt outgoing response
        if ($response->getContent()) {
            $encrypted = Crypt::encryptString($response->getContent());
            $response->setContent($encrypted);
        }

        return $response;
    }
}
