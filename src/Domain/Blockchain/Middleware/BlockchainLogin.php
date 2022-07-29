<?php

namespace Domain\Blockchain\Middleware;

use Closure;
use Domain\Settings\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class BlockchainLogin
{
    public function handle(Request $request, Closure $next): mixed
    {
        $accessToken = Setting::where('name', 'blockchain_access_token')->first();
        $response = Http::withToken($accessToken->value)->get('https://api.edbit.io/user/getCurrentUser');

        $status = $response->status();

        if ($status >= 200 && $status < 300) {
            return $next($request);
        }
        $email = Setting::where('name', 'blockchain_email')->first()->value;
        $password = Setting::where('name', 'blockchain_password')->first()->value;
        $password = Crypt::decryptString($password);

        $response = Http::post('https://api.edbit.io/auth/login',
            [
                'email' => $email,
                'password' => $password
            ]);

        $response = json_decode($response);

        if (!$response->error) {
            $settings = Setting::upsert(
                ['name' => 'blockchain_access_token', 'value' => $response->accessToken],
                ['name'], ['value']
            );
            if ($settings) {
                return $next($request);
            }
            return response('Bad Request', 400);
        }
        return response('Unauthorized', 401);
    }
}
