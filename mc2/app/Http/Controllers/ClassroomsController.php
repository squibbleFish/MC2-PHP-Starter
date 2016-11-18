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

    public function get_classrooms( $id ) {

    }

    public function classroom_details() {

    }

    public function edit_classroom() {

    }

}