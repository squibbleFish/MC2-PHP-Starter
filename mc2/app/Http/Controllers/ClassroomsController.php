<?php
namespace App\Http\Controllers;

use App\User;
use App\Guardians;
use App\Classrooms;

use App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Requests;

Use Log;

/**
 * Class ClassroomsController
 * @package App\Http\Controllers
 */
class ClassroomsController extends Controller
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
     *
     * @todo Might need to pass in data params to make this more performant.
     * @todo We will see once front end is better fleshed out too.
     */
    public function get_classrooms( $uid ) {
        $c = User::select('*')->where('_id', $uid)->first()->classrooms;
        return $c;
    }

    /**
     * @param $uid user id
     * @param $id object id
     * @return mixed
     */
    public function classroom_details( $uid, $id ) {
        return Classrooms::where('_id', $id)->first();
    }

    /**
     * @param $uid
     * @param $id
     */
    public function edit_classroom( $uid, $id ) {

        $c = Classrooms::where([
            ['user_id', $uid],
            ['_id', $id]
        ])->first();

        $class_keys = array_keys(self::$classroom_schema);

        foreach ($class_keys as $key) {
            if ( $key !== 'location' ) {
                $c->$key = $this->request->has($key) ? $this->request->input($key) : $c->$key;
            }
        }

        if ( $this->request->has('location') ) {
            $add_input = $this->request->input('location');
            $loc = $this->location_lookup( $this->request->input('location') );
            $add_input['lng'] = $loc['lng'];
            $add_input['lat'] = $loc['lat'];
            $c->location = $add_input;
        }

        try {
            $c->update();
        } catch ( \Exception $e ) {
            Log::error( "Database Error: {$e}" );
            return json_encode( array(
                'success' => false,
                'message' => $e
            ) );
        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'classroom' => $c
            )
        ) );
    }

    /**
     * When added creates the Class to User relationship
     *
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
         * handling location separately since we need to find lat and lng for our map
         */
        if ( $this->request->has('location') ) {
            $add_input = $this->request->input('location');
            $loc = $this->location_lookup( $this->request->input('location') );
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
     * Does not delete record, only sets the meeting to false.
     * Acts like a soft delete so class can still be edited.
     * @param $uid
     * @param $id
     * @return string
     */
    public function remove_classroom( $uid, $id ) {
        $c = Classrooms::where([
            ['user_id', $id],
            ['_id', $id]
        ])->first();

        $c->canceled = true;

        try {
            $c->update();
        } catch ( \Exception $e ) {
            Log::error( "Database Error: {$e}" );
            return json_encode( array(
                'success' => false,
                'message' => $e
            ) );
        }

        /**
         * @todp all guardians who's children are enrolled should be notified of cancellation
         */
        $m = new Mail( $u );

        return json_encode( array(
            'success' => true,
            'message' => array(
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