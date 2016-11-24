<?php

namespace App\Http\Controllers;

use App\Notifcations;
use App\User;
use App\Classrooms;
use App\Children;
use App\Guardians;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    protected static $schema = array(
        'message'   => '',
        'type'      => '',
        'from'      => '',
        'to'        => '',
        'dismissed' => false
    );

    /**
     * NotificationController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct( Request $request, Response $response ) {
        $this->request = $request;
        $this->response =$response;
    }

    public function create() {

    }

    public function dismiss() {

    }

    public function delete() {

    }

}