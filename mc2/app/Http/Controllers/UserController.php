<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// Database Helpers
use App\User as User;
use App\Alpha as Alpha;
use Mockery\CountValidator\Exception;
use Log;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * @var array
     */
    protected static $user_schema = array(
        'fName'           => '',
        'lName'           => '',
        'email'           => '',
        'password'        => '',
        'tempPassword'    => '',
        'location'      => array(
            'address'   => '',
            'address2'  => '',
            'city'      => '',
            'state'     => '',
            'zip'       => '',
            'lang'      => '',
            'lat'       => ''
        ),
        'age'             => '',
        'children'        => array(),
        'relationship'    => '',
        'education'       => '',
        'religion'        => '',
        'maritalStatus'   => '',
		'languages'       => array(),
        'host'            => false,
        'backgroundCheck' =>  false,
        'classrooms'      => array(),
        'notifications'   => array(),
        'ratings'         => 0,
		'reviews'         => 0
    );

    /**
     * @var array
     */
    protected static $alpha_static = array(
        'email' => '',
        'code'  => '',
        'used'  => false
    );

    /**
     * @var int
     */
    CONST ALPHA_TOTAL = 200;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * UserController constructor.
     * @param Response $response
     * @param Request $request
     */
    public function __construct( Response $response, Request $request ) {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function alpha_hack() {
        $code = md5(time());

        if ( !$this->request->has('email') ) {
            return json_encode( array( 'success' => false, 'message' => 'email missing' ) );
        }

        self::$alpha_static['email'] = $this->request->input('email');
        self::$alpha_static['code'] = $code;

        $alpha_schema_keys = array_keys( self::$alpha_static );
        $used_query = json_decode( Alpha::select('used')->where('used', true )->get() , true );
        if ( count($used_query) >= self::ALPHA_TOTAL ) {
            return json_encode( array( 'success' => false, 'message' => 'no vacancy' ) );
        }

        $alpha = new Alpha();
        foreach ( $alpha_schema_keys as $keys ) {
            $alpha->$keys = self::$alpha_static[$keys];
        }
        if ( !$alpha->save() ) {
            return json_encode( array( 'success' => false, 'message' => 'database error' ) );
        }


        $new_user = $this->user_add( self::$alpha_static );

        /**
         * @todo Add Code to send email
         */
//        if ( $new_user ) {
//                //
//            $mail_api = env('SEND_GRID');
//            $from = new SendGrid\Email(null, "test@example.com");
//            $subject = "Hello World from the SendGrid PHP Library!";
//            $to = new SendGrid\Email(null, "test@example.com");
//            $content = new SendGrid\Content("text/plain", "Hello, Email!");
//            $mail = new SendGrid\Mail($from, $subject, $to, $content);
//
//            $apiKey = getenv('SENDGRID_API_KEY');
//            $sg = new \SendGrid($apiKey);
//
//            $response = $sg->client->mail()->send()->post($mail);
//            echo $response->statusCode();
//            echo $response->headers();
//            echo $response->body();
//        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user' => $this->request->input('email'),
                'code' => $code
            )
        ) );

    }

    /**
     * @param array $user_data
     * @return bool
     */
    public function user_add( $user_data = array() ) {

        self::$user_schema['email'] = $user_data['email'];
        self::$user_schema['tempPassword'] = $user_data['code'];
        $user_keys = array_keys(self::$user_schema);

        $user = new User();
        foreach ( $user_keys as $keys ) {
            $user->$keys = self::$user_schema[$keys];
        }

        try {
            return $user->save();
        }catch ( \Exception $e ) {
            Log::error($e);
            return false;
        }

    }
}