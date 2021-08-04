<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function c1()
    {
        $token =  $this->generateJwt('admin1@gmail.com');
        $rn = 'Class One';
        return view('class',compact('token','rn'));
    }

    public function c2()
    {
        $token =  $this->generateJwt('admin2@gmail.com');
        $rn = 'Class Two';
        return view('class',compact('token','rn'));
    }

    public function c3()
    {
        $token =  $this->generateJwt('admin3@gmail.com');
        $rn = 'Class Three';
        return view('class',compact('token','rn'));
    }
    public function c4()
    {
        $token =  $this->generateJwt('admin4@gmail.com');
        $rn = 'Class Four';
        return view('class',compact('token','rn'));
    }

    private function generateJwt($admin)
    {
        /**
         * Change the variables below.
         */
        $API_KEY=env('JITSI_API_KEY');
        $APP_ID=env('JITSI_APP_ID'); // Your AppID (previously tenant)
        $USER_EMAIL=auth()->user()->email;
        $USER_NAME=auth()->user()->name;
        $USER_IS_MODERATOR =  $USER_EMAIL == $admin ? true : false;
        $USER_AVATAR_URL="";
        $USER_ID=auth()->id();
        $LIVESTREAMING_IS_ENABLED=true;
        $RECORDING_IS_ENABLED=true;
        $OUTBOUND_IS_ENABLED=false;
        $TRANSCRIPTION_IS_ENABLED=true;
        $EXP_DELAY_SEC=7200;
        $NBF_DELAY_SEC=10;
        ///

        /**
         * We read the JSON Web Key (https://tools.ietf.org/html/rfc7517) 
         * from the private key we generated at https://jaas.8x8.vc/#/apikeys .
         * 
         * @var \Jose\Component\Core\JWK jwk
         */
        $jwk = JWKFactory::createFromKeyFile(base_path('.jitsi-private.key'));


        /**
         * Setup the algoritm used to sign the token.
         * @var \Jose\Component\Core\AlgorithmManager $algorithm
         */
        $algorithm = new AlgorithmManager([
            new RS256()
        ]);

        /**
         * The builder will create and sign the token.
         * @var \Jose\Component\Signature\JWSBuilder $jwsBuilder
         */
        $jwsBuilder = new JWSBuilder($algorithm);

        /**
         * Must setup JaaS payload!
         * Change the claims below or using the variables from above!
         */
        $payload = json_encode([
            'iss' => 'chat',
            'aud' => 'jitsi',
            'exp' => time() + $EXP_DELAY_SEC,
            'nbf' => time() - $NBF_DELAY_SEC,
            'room'=> '*',
            'sub' => $APP_ID,
            'context' => [
                'user' => [
                    'moderator' => $USER_IS_MODERATOR ? "true" : "false",
                    'email' => $USER_EMAIL,
                    'name' => $USER_NAME,
                    'avatar' => $USER_AVATAR_URL,
                    'id' => $USER_ID
                ],
                'features' => [
                    'recording' => $RECORDING_IS_ENABLED ? "true" : "false",
                    'livestreaming' => $LIVESTREAMING_IS_ENABLED ? "true" : "false",
                    'transcription' => $TRANSCRIPTION_IS_ENABLED ? "true" : "false",
                    'outbound-call' => $OUTBOUND_IS_ENABLED ? "true" : "false"
                ]
            ]
        ]);

        /**
         * Create a JSON Web Signature (https://tools.ietf.org/html/rfc7515)
         * using the payload created above and the api key specified for the kid claim.
         * 'alg' (RS256) and 'typ' claims are also needed.
         */
        $jws = $jwsBuilder
                ->create()
                ->withPayload($payload)
                ->addSignature($jwk, [
                    'alg' => 'RS256',
                    'kid' => $API_KEY,
                    'typ' => 'JWT'
                ])
                ->build();

        /**
         * We use the serializer to base64 encode into the final token.
         * @var \Jose\Component\Signature\Serializer\CompactSerializer $serializer
         */
        $serializer = new CompactSerializer();
        $token = $serializer->serialize($jws, 0);

        /**
         * Write the token to standard output.
         */
        return $token;
    }
}
