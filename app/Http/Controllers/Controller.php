<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function http( $type = "GET", $url = "", $data = [] ) {
        $request = Http::withHeaders([
            'Authorization' => 'Bearer c2b647092cbb6f3ba01f0bc66b42f502'
        ]);

        if ( $type == "GET" ) {
            $request = $request->get( $url );

        } else {
            $request = $request->post( $url, $data );
        }

        return $request;
    }
}
