<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Database Helpers
use App\User as User;
use App\Alpha as Alpha;
use App\Guardians as Guardians;
use Mockery\CountValidator\Exception;
use Log;
use Requests;

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
            'lng'      => '',
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
        'backgroundCheck' => false,
        'classrooms'      => array(),
        'notifications'   => array(),
        'totalHours'      => 0,
        'ratings'         => 0,
		'reviews'         => 0
    );

    /**
     * @var array
     */
    protected static $alpha_schema = array(
        'email' => '',
        'code'  => '',
        'used'  => false
    );

    /**
     * @var array
     */
    protected static $guardian_schema = array(

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
     * @param $id
     * @return mixed
     */
    public function get_user($id) {
        return User::where('_id', $id)->first();
    }

    /**
     * @return string
     */
    public function alpha_hack() {
        $code = md5(time());

        if ( !$this->request->has('email') ) {
            return json_encode( array( 'success' => false, 'message' => 'email missing' ) );
        }

        self::$alpha_schema['email'] = $this->request->input('email');
        self::$alpha_schema['code'] = $code;

        $alpha_schema_keys = array_keys( self::$alpha_schema );
        $used_query = json_decode( Alpha::select('used')->where('used', true )->get() , true );
        if ( count($used_query) >= self::ALPHA_TOTAL ) {
            return json_encode( array( 'success' => false, 'message' => 'no vacancy' ) );
        }

        $alpha = new Alpha();
        foreach ( $alpha_schema_keys as $keys ) {
            $alpha->$keys = self::$alpha_schema[$keys];
        }
        if ( !$alpha->save() ) {
            return json_encode( array( 'success' => false, 'message' => 'database error' ) );
        }


        $new_user = $this->user_add( self::$alpha_schema );

        /**
         * send out email for all new users
         */
        if ( $new_user ) {
            $email = new Mail( $this );
            Log::info( "email_response: {$email}" );
        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user'  => $this->request->input('email'),
                'code'  => $code,
                'email' => $email
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

    /**
     * @return string
     */
    public function user_confirm() {
        $code = $this->request->input('user_code');
        $email = $this->request->input('email');
        $user = null;
        try {
            $user = User::where([
                [ 'email' , '=',  $email ],
                [ 'tempPassword', '=', $code ]
            ])->first();
        }catch (\Exception $e ) {
            LOG::error($e);
        }

        if ( is_null($user) ) {
            return json_encode( array( 'success' => false, 'message' => 'user does not exist' ) );
        }

        try {
            Alpha::where('code', $code )->update(['used' => true]);
        } catch ( \Exception $e ) {
            Log::error($e);
            return json_encode( array( 'success' => false, 'message' => $e ) );
        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user' => $user
            )
        ) );
    }

    /**
     * @param $id
     * @return string
     */
    public function update_password($id) {
        $password = $this->request->input('password');
        $hashed = password_hash( $password, PASSWORD_BCRYPT );
        try {
            User::where('_id', $id )->update([
                ['password', $hashed],
                ['tempPassword', '']
            ]);
        } catch ( \Exception $e ) {
            Log::error($e);
            return json_encode( array( 'success' => false, 'message' => $e ) );
        }

        return json_encode( array(
            'success' => true ,
            'message' => 'password updated'
        ) );
    }

    /**
     * @return string
     */
    public function authenticate_user_pass() {
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        try {
            $user = User::where('email', $email )->first();
        } catch ( \Exception $e ) {
            Log::error($e);
            return json_encode( array( 'success' => false, 'message' => "{$email} user not found" ) );
        }

        if ( !password_verify($password, $user->password)) {
            Log::info( "{$email} password not verified" );
            return json_encode( array(
                'success' => false,
                'message' => "{$email} password not verified"
            ) );
        }

        return json_encode( array(
            'success' => true,
            'message' =>  array(
                'user' => $user
            )
        ) );

    }

    /**
     * Edits profile information
     * @param $id
     * @return string
     */
    public function edit_user($id) {
        $u = $this->get_user($id);
        $u_keys = array_keys(self::$user_schema);

        foreach ($u_keys as $key) {
            if ( $key !== 'location' ) {
                $u->$key = $this->request->has($key) ? $this->request->input($key) : $u->$key;
            }
        }

        /**
         * handling location separately since we need to find lat and long for our map
         */
        if ( $this->request->has('location') ) {
            $add_input = $this->request->input('location');
            $loc = json_decode( $this->location_lookup( $this->request->input('location') ), true ) ;
            $add_input['lng'] = $loc['lng'];
            $add_input['lat'] = $loc['lat'];
            $u->location = $add_input;
        }

        try {
            $u->save();
        } catch (\Exception $e ) {
            Log::error($e);
            return json_encode( array( 'success' => false, 'message' => $e ) );
        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user' => $u
            )
        ) );

    }

    /**
     * @param array $address
     * @return mixed
     */
    public function location_lookup( $address = array() ) {

        //@todo just for testing
        if ( empty( $address ) ) {
            $address = $this->request->input('location');
        }

        $loc = implode('+', $address);
        $loc = str_replace(' ', '+', $loc );
        $res = Requests::get( "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$loc}");
        $body = json_decode( $res->body, true );
        return $body['results'][0]['geometry']['location'];
    }

}