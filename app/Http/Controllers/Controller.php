<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @description Initialize the NZBN External API Options
     * @param $key bearer, host, token_url, or token_hash
     * @return String value from .env
     */
    public function getOptions( $key ) {
        $options = null;
        $mode = config( 'external_api.env' );
        \Log::info( "Mode: {$mode}" );

        if ( $mode == 'live' ) {
            $options = [
                'bearer' => config( 'external_api.bearer_live' ),
                'host' => config( 'external_api.host_live' ),
                'token_url' => config( 'external_api.token_url' ),
                'token_hash' => config( 'external_api.token_live_hash' )
            ];

        } else {
            $options = [
                'bearer' => config( 'external_api.bearer_local' ),
                'host' => config( 'external_api.host_local' ),
                'token_url' => config( 'external_api.token_url' ),
                'token_hash' => config( 'external_api.token_local_hash' )
            ];
        }

        return $options[$key];
    }

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
        $host = $this->getOptions( 'host' );
        \Log::info( "Host: {$host}" );

        return $host;
    }

    /**
     * @description Get token from NZBN API
     */
    public function getToken() {
        try {
            $client = new Client();
            $tokenUrl = $this->getOptions( 'token_url' );
            $tokenHash = $this->getOptions( 'token_hash' );

            $request = $client->request( "POST", $tokenUrl, [
                'headers' => [
                    'Accept' => '*/*',
                    'Authorization' => 'Basic ' . $tokenHash
                ]
            ] );
            
            $response = json_decode( $request->getBody() );
            if ( $response ) return $response->access_token;

            return false;

        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );
            \Log::error( $e->getTraceAsString() );

            return false;
        }
    }

    /**
     * @description Initialize the HTTP request to external IP
     */
    public function http( $type = "GET", $url = "", $data = [] ) {
        try {
            \Log::info( "URL: {$url}" );
            $request = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getToken()
            ]);
            $request = ( $type == "GET" ) ? $request->get( $url ) : $request->post( $url, $data );
            return $request->json();

        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );
            \Log::error( $e->getTraceAsString() );

            return false;
        }
    }
}
