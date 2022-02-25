<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function validateId( Request $request ) {
        $url = "https://sandbox.api.business.govt.nz/services/v4/nzbn/entities/{$request->id}";
        $validateId = $this->http( "GET", $url );

        return response()->json([
            'success' => true,
            'message' => 'You made it here!',
            'data' => $validateId
        ]);
    }
}
