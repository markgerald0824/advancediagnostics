<?php

return [
  'env' => env( 'API_ENV', 'local' ),
  'host' => env( 'API_HOST_ENV', 'sandbox' ),

  'bearer_live' => env( 'API_LIVE_BEARER', '' ),
  'host_live' => env( 'API_LIVE_HOST', 'https://api.business.govt.nz/services/v4/nzbn' ),

  'bearer_local' => env( 'API_LOCAL_BEARER', '' ),
  'host_local' => env( 'API_SANDBOX_HOST', 'https://sandbox.api.business.govt.nz/services/v4/nzbn' )
];
