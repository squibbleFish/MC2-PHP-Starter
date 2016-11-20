<?php
namespace App\Http\Controllers;

use App\User;
use App\Guardians;
use App\Classrooms;

use App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

Use Log;

/**
 * Class ClassroomsControllers
 * @package App\Http\Controllers
 */
class ClassroomsControllers extends Controller
{

    /**
     * @var array
     */
    protected static $classroom_schema = array(
        'uid'            => '',
        'name'           => '',
        'date'           => '',
        'length'         => '',
        'startTime'      => '',
        'endTime'        => '',
        'availableSeats' => '',
        'children'       => array(),
        'details'        => '',
        'canceled'       => false,
        'location'       => array(
            'address'  => '',
            'address2' => '',
            'city'     => '',
            'state'    => '',
            'zip'      => '',
            'lng'      => '',
            'lat'      => ''
        )
    );

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * ClassroomsControllers constructor.
     * @param Response $response
     * @param Request $request
     */
    public function __construct( Response $response, Request $request ) {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function get_classrooms( $uid ) {
        return User::where('_id', $uid)->classrooms();
//        return Classrooms::where('uid', $uid )->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function classroom_details( $uid, $id ) {
        return Classrooms::where([
            ['_id', $id]
        ])->first();
    }

    /**
     * @param $uid
     * @param $id
     */
    public function edit_classroom( $uid, $id ) {

    }

    /**
     * @param $uid
     * @return string
     */
    public function add_classroom( $uid ) {
        $c = new Classrooms();
        $u = User::where('_id', $uid)->first();
        $class_keys = array_keys(self::$classroom_schema);

        foreach ($class_keys as $key) {
            if ( $key !== 'location' ) {
                $c->$key = $this->request->has($key) ? $this->request->input($key) : $c->$key;
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
            $c->location = $add_input;
        }
        $c->uid = $uid;

        $c = $u->classrooms()->save($c);

        return json_encode( array(
            'success' => true,
            'message' => array(
                'classrooms' => $c
            )
        ) );
    }

    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function remove_classroom( $uid, $id ) {
        $c = Classrooms::where('_id', $id)->first();
        $u = User::where('_id', $uid)->first();
        $c->canceled = true;
        $c = $u->classrooms()->save($c);

        /**
         * @todp all guardians who's children are enrolled should be notified of cancellation
         */
        $m = new Mail( $u );

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user'       => $u,
                'classrooms' => $c
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