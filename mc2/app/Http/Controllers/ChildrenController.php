<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Mail;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\GuardiansController;

use App\Children;
use App\Guardians;
use App\Classrooms;
use App\User;
use App\Notifcations;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Log;

class ChildrenController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected static $child_schema = array(
        'fName'             => '',
        'lName'             => '',
        'email'             => '',
        'age'               => '',
        'health'            => array(
            'allergies'  => '',
            'conditions' => '',
            'details'    => ''
        ),
        'emergency_contact' => array(
            'fName'      => '',
            'lName'      => '',
            'email'      => '',
            'phone'      => ''
        )
    );

    /**
     * ChildrenController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct( Request $request, Response $response ){
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param $uid
     * @return string
     */
    public function add_child( $uid ) {
        $c = new Children();
        $u = User::where( '_id', $uid )->first();

        $c->fName                     = $this->request->input('fName');
        $c->lName                     = $this->request->input('lName');
        $c->email                     = $this->request->input('email');
        $c->age                       = $this->request->input('age');
        $c->health['allergies']       = $this->request->input('allergies');
        $c->health['conditions']      = $this->request->input('conditions');
        $c->health['details']         = $this->request->input('details');
        $c->emergencyContact['fName'] = $this->request->input('cFName');
        $c->emergencyContact['lName'] = $this->request->input('cLName');
        $c->emergencyContact['email'] = $this->request->input('cEmail');
        $c->emergencyContact['phone'] = $this->request->input('cPhone');

        try {
            $c = $u->children()->save($c);
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
                'child' => $c
            )
        ) );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function get_all( $uid ) {
        return Children::where( 'user_id', $uid )->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function get_single( $uid, $id ) {
        return Children::where([
            ['user_id', $uid],
            ['_id', $id]
        ])->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function edit_child( $uid, $id ) {
        $c = User::where('_id', $uid )->first()->children()->where('_id', $id)->first();

        $c->fName                     = $this->request->has('fName')      ? $this->request->input('fName')      : $c->fName;
        $c->lName                     = $this->request->has('lName')      ? $this->request->input('lName')      : $c->lName;
        $c->email                     = $this->request->has('email')      ? $this->request->input('email')      : $c->email;
        $c->age                       = $this->request->has('age')        ? $this->request->input('age')        : $c->age;
        $c->health['allergies']       = $this->request->has('allergies')  ? $this->request->input('allergies')  : $c->health['allergies'];
        $c->health['conditions']      = $this->request->has('conditions') ? $this->request->input('conditions') : $c->health['conditions'];
        $c->health['details']         = $this->request->has('details')    ? $this->request->input('details')    : $c->health['details'];
        $c->emergencyContact['fName'] = $this->request->has('cFName')     ? $this->request->input('cFName')     : $c->emergencyContact['fName'];
        $c->emergencyContact['lName'] = $this->request->has('cLName')     ? $this->request->input('cLName')     : $c->emergencyContact['lName'];
        $c->emergencyContact['email'] = $this->request->has('cEmail')     ? $this->request->input('cEmail')     : $c->emergencyContact['email'];
        $c->emergencyContact['phone'] = $this->request->has('cPhone')     ? $this->request->input('cPhone')     : $c->emergencyContact['phone'];

        try {
            $c = $c->update();
        } catch (\Exception $e ) {
            Log::error( "Database Error: {$e}" );
            return json_encode( array(
                'success' => false,
                'message' => $e
            ) );
        }

        return json_encode( array(
            'success' => true,
            'message' => array(
                'child' => $c
            )
        ) );
    }


    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function delete_child( $uid, $id ) {

        $c = User::where('_id', $uid)->first()->children()->where('_id', $id)->first();

        try {
            $c->delete();
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
                'child' => $c
            )
        ) );
    }

    /**
     * Registers child for class / time slot
     * @todo Add notification of successful registration
     * @param $uid
     * @param $id
     */
    public function register_child( $uid, $id ) {
        
    }

}