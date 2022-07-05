<?php

namespace Domain\Blockchain\Controllers;

use App\Http\Controllers\Controller;
use Domain\Files\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BlockchainController extends Controller
{

    public function saveFileBlockchain(Request $request)
    {
        $token = $request->bearerToken();
        $file_id = $request->fileId;;
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

                $response = json_decode($response);


                $file->txLink = $response->transactionLink;
                $file->cerLink = $response->certificateLink;
                $file->fileHash = $response->fileHash;
                $file->accessToken = $response->accessToken;
                $file->is_save_to_blockchain = true;
                $file->save();
                return $file;
            }
        } else {
            return false;
        }
    }

}
