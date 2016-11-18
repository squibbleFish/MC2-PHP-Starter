<?php

namespace App\Http\Controllers;

use App\Children;
use App\Guardians;
use App\Classrooms;
use App\User;

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
     * @return mixed
     */
    public function get_all( $uid ) {
        return Children::where( 'uid', $uid )->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function get_single( $uid, $id ) {
        return Children::where([
            ['uid', $uid],
            ['id', $id]
        ])->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function edit_child( $uid, $id ) {

        $c = array();

        return json_encode( array(
            'success' => true,
            'message' => array(
                'child' => $c
            )
        ) );
    }

    /**
     * @param $uid
     * @return string
     */
    public function add_child( $uid ) {

        $c = array();

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

        $c = array();

        return json_encode( array(
            'success' => true,
            'message' => array(
                'child' => $c
            )
        ) );
    }

}