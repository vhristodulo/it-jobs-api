<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller 
{
    /**
     * Request instance
     *
     * @var object
     */
    private $request;

    /**
     * Create a new controller instance
     *
     * @param object $request
     * @return void
     */
     public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param object $user
     * @return string
     */
    protected function jwt(User $user) :string {
        $issuer = 'itjobs';
        $userId = $user->id;
        $currentTime = time();
        $expirationTime = time() + 3600;
        // $expirationDate = date('Y-m-d H:i:s', $expirationTime);

        $payload = [
            'iss' => $issuer,
            'sub' => $userId,
            'iat' => $currentTime, 
            'exp' => $expirationTime
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'));

        // $userSession = [
        //     'user_id' => $user->id,
        //     'token' => $token,
        //     'expiration_date' => $expirationDate,
        // ];

        // save session to db user_sessions table //
        
        return $token;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct
     * 
     * @param object $user
     * @return object
     */
    public function authenticate(User $user) :object {
        $this->validate(
            $this->request,
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'E-mail adresa je obavezna',
                // 'email.email' => 'E-mail adresa nije u ispravnom formatu',
                'password.required' => 'Lozinka je obavezna'
            ]
        );

        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            $statusCode = 400;
            $result = [
                'status' => [
                    'success' => false,
                    'code' => $statusCode,
                    'message' => 'E-mail adresa ili lozinka nije ispravna'
                ],
                'data' => []
            ];
            return response()->json($result, $statusCode);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            $token = $this->jwt($user);
            $statusCode = 200;
            $result = [
                'status' => [
                    'success' => true,
                    'code' => $statusCode,
                    'message' => ''
                ],
                'data' => [
                    'token' => $token
                ]
            ];
            return response()->json($result, $statusCode);
        }

        $statusCode = 400;
        $result = [
            'status' => [
                'success' => false,
                'code' => $statusCode,
                'message' => 'E-mail adresa ili lozinka nije ispravna'
            ],
            'data' => []
        ];
        return response()->json($result, $statusCode);
    }

}