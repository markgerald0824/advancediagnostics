<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function validateId( Request $request ) {
        $url = "https://sandbox.api.business.govt.nz/services/v4/nzbn/entities/{$request->id}";
        $validate = json_decode( $this->http( "GET", $url ) );
        $status = $validate->status;
        $message = ( $status == 400 ) ? $validate->errorDescription : "NZBN Number Found!";

        return response()->json([
            'success' => true,
            'message' => 'You made it here!',
            'data' => [
                'api_status' => $status,
                'api_message' => $message
            ]
        ]);
    }
}
