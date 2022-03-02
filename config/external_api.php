<?php

return [
  'env' => env( 'API_ENV', 'local' ),
  'host' => env( 'API_HOST_ENV', 'sandbox' ),

  'token_url' => env( 'API_TOKEN', '' ),
  'token_live_hash' => env( 'API_LIVE_TOKEN_HASH', '' ),
  'token_local_hash' => env( 'API_SANDBOX_TOKEN_HASH', '' ),

  'bearer_live' => env( 'API_LIVE_BEARER', '' ),
  'host_live' => env( 'API_LIVE_HOST', '' ),

  'bearer_local' => env( 'API_LOCAL_BEARER', '' ),
  'host_local' => env( 'API_SANDBOX_HOST', '' )
];
