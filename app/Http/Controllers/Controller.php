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

    /**
     * @description Get the External API keys
     */
    public function getBearer() {
        $mode = config( 'external_api.env' );
        $bearer = ( $mode == 'live' ) ? config( 'external_api.bearer_live' ) : config( 'external_api.bearer_local' );
        \Log::info( "Mode: {$mode} -- Bearer: {$bearer}" );

        return $bearer;
    }

    /**
     * @description Get the External Host
     */
    public function getHost() {
        $mode = config( 'external_api.host' );
        $host = ( $mode == 'live' ) ? config( 'external_api.host_live' ) : config( 'external_api.host_local' );
        \Log::info( "Mode: {$mode} -- Host: {$host}" );

        return $host;
    }

    /**
     * @description Initialize the HTTP request to external IP
     */
    public function http( $type = "GET", $url = "", $data = [] ) {
        try {
            \Log::info( "URL: {$url}" );
            $request = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getBearer()
            ]);
            $request = ( $type == "GET" ) ? $request->get( $url ) : $request->post( $url, $data );
            return $request;

        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );
            \Log::error( $e->getTraceAsString() );

            return false;
        }
    }
}
