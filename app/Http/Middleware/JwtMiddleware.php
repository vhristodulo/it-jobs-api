<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{

    /**
     * Handle request
     * 
     * @throws ExpiredException
     * @throws Exception
     * @param object $request
     * @param object $next
     * @return array
     */
    public function handle(object $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        $header = explode(' ', $authHeader);
        $token = null;
        if (isset($header[1])) $token = $header[1];
        if (empty($token) || $token == 'null') $token = null;

        if(!$token) {
            $statusCode = 400;
            $result = [
                'status' => [
                    'success' => false,
                    'code' => $statusCode,
                    'message' => 'Nemate prava da pristupite ovom sadržaju'
                ],
                'data' => []
            ];
            return response()->json($result, $statusCode);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            $statusCode = 400;
            $result = [
                'status' => [
                    'success' => false,
                    'code' => $statusCode,
                    'message' => 'Sesija je istekla - molimo prijavite se ponovo'
                ],
                'data' => []
            ];
            return response()->json($result, $statusCode);
        } catch(Exception $e) {
            $statusCode = 400;
            $result = [
                'status' => [
                    'success' => false,
                    'code' => $statusCode,
                    'message' => 'Greška prilikom dekodiranja tokena'
                ],
                'data' => []
            ];
            return response()->json($result, $statusCode);
        }

        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }

}