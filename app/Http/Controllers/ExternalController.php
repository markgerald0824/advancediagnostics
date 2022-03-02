<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function validateId( Request $request ) {
        try {
            $url = "{$this->getHost()}/entities/{$request->id}";
            $validate = $this->http( "GET", $url );

            if ( ! $validate ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Opps! You made it here with errors.'
                ]);
            }

            $status = null;

            if ( isset( $validate['nzbn'] ) ) {
                $status = 200;
                $message = "NZBN Business Number has been verified.";
            } else {
                $status = $validate['status'];
                $message = ( $status == 400 ) ? $validate['errorDescription'] : "NZBN Number Found!";
            }
            

            return response()->json([
                'success' => true,
                'message' => 'You made it here!',
                'data' => [
                    'api_status' => $status,
                    'api_message' => $message
                ]
            ]);
            
        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );
            \Log::error( $e->getTraceAsString() );

            return response()->json([
                'success' => false,
                'message' => 'Opps! You made it here with errors [e].'
            ]);
        }
    }
}
