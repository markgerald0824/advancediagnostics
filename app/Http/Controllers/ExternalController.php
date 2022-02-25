<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function validateId( Request $request ) {
        return response()->json([
            'success' => true,
            'message' => 'You made it here!',
            'data' => $request->all()
        ]);
    }
}
