<?php

namespace Domain\Blockchain\Controllers;

use App\Http\Controllers\Controller;
use Domain\Files\Models\File;
use Domain\Settings\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;


class BlockchainController extends Controller
{

    public function loginToBlockchain(Request $request)
    {
        $response = Http::post('https://api.edbit.io/auth/login', $request->all());
        $response = json_decode($response);
        if (!$response->error) {
            $settings = Setting::upsert([
                ['name' => 'blockchain_email', 'value' => $request->email],
                ['name' => 'blockchain_password', 'value' => Crypt::encryptString($request->password)],
                ['name' => 'blockchain_access_token', 'value' => $response->accessToken]
            ], ['name'], ['value']);

            if ($settings) {
                return response('OK', 200);
            }
            return response('Bad Request', 400);
        }
        return response('Unauthorized', 401);
    }


    public function saveFileBlockchain(Request $request)
    {
        $token = Setting::where('name', 'blockchain_access_token')->first()->value;

        $file_id = $request->fileId;
        $file = File::where('id', $file_id)->first();

        if ($file) {
            if (!$file->is_save_to_blockchain) {
                $filePath = "files/$file->user_id/$file->basename";

                $f_file = file_get_contents(storage_path('app/' . $filePath));

                set_time_limit(200);

                $response = Http::timeout(200)
                    ->attach('file', $f_file, $file->basename)
                    ->withToken($token)
                    ->post('https://api.edbit.io/certificate/issue',
                        [
                            "data" => json_encode(["certificate" =>
                                [
                                    "folderName" => $request->input('folderName'),
                                    "type" => $request->input('type')
                                ]
                            ])
                        ]
                    );

                if ($response->status() === 200) {
                    $response = json_decode($response);

                    $file->txLink = $response->transactionLink;
                    $file->cerLink = $response->certificateLink;
                    $file->fileHash = $response->fileHash;
                    $file->accessToken = $response->accessToken;
                    $file->is_save_to_blockchain = true;
                    $file->save();
                    return response($file, 200);
                }
                return response('Not Found', 404);
            }
        }
        return response('Not Found', 404);
    }

    public function getFolders()
    {
        $accessToken = Setting::where('name', 'blockchain_access_token')->first()->value;
        $response = Http::withToken($accessToken)->get('https://api.edbit.io/dir/getFolders');
        $response = json_decode($response);
        if (!$response->error) {
            return response($response->folders, 200);
        }
        return response('Not Found', 404);
    }

}
