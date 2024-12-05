<?php

namespace App\Services;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateFirebaseToken(string $uid, $claims = [], $ttl = 3600)
    {
        // $fake = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay01ajRtd0BieWFoZWthcGgyMDI0LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwic3ViIjoiZmlyZWJhc2UtYWRtaW5zZGstNWo0bXdAYnlhaGVrYXBoMjAyNC5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHl0b29sa2l0Lmdvb2dsZWFwaXMuY29tL2dvb2dsZS5pZGVudGl0eS5pZGVudGl0eXRvb2xraXQudjEuSWRlbnRpdHlUb29sa2l0IiwiaWF0IjoxNzI2OTQ3MDAwLCJleHAiOjE3MjY5NTA2MDAsInVpZCI6IjIifQ.G2eZ1NqMGNP4IYVL7SUPVE26bAwe4_BdcV1fH0m1LIhSr5w1ZHaZSiEQlBh_xDcujXeEeWsTlAOjIeViOxSZgmx5fpaemZGuzKFwYDgrqFhxEQ59G2DOF_FbQy0ux2GfOGFWSNKTW8oDv-7Snm-caM7BYGzSDphByjAb53gxl2qHp0FBMV5ClId81vLby-gkzum2uGPKUc-yHgfnluiSRY57ouu7PAiEM8ZzMRZmTtxvEzH4mYz4aYDsjdsTWoxD14J9CP_MUG79eXPn9-vLzJ27jN1EdyN-HJKUleEjVMIKN4OUuw7N3K0vFjebF5B4XO33js3Wi4G7UOYO9JyCTw';
        // return $fake;
        $auth = Firebase::auth();
        $token = $auth->createCustomToken($uid, $claims, $ttl);
        return $token->toString();
    }
}
