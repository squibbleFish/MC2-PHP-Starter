<?php
namespace App\Http\Controllers;

use App\User;
use App\Guardians;
use App\Classrooms;

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
        return Classrooms::where('uid', $uid )->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function classroom_details( $uid, $id ) {
        return Classrooms::where([
            ['uid', $uid],
            ['_id', $id]
        ])->first();
    }

    public function edit_classroom( $uid, $id ) {

    }


    public function add_classroom( $uid ) {

    }

    public function remove_classroom( $uid, $id ) {

    }
}