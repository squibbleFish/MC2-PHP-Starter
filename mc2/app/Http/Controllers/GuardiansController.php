<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Guardians;
Use Log;

/**
 * Class GuardianController
 * @package App\Http\Controllers
 */
class GuardiansController extends Controller
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
    protected static $guardian_schema = array(
        'uid'       => '',
        'fName'     => '',
        'lName'     => '',
        'email'     => ''
    );

    /**
     * GuardianController constructor.
     */
    public function __construct( Request $request, Response $response ) {
        $this->request = $request;
        $this->response = $response;
    }

    public function get_all( $id ) {
        return Guardians::where('uid', $id)->first();
    }

    public function get_single( $id ) {
        return Guardians::get();
    }

    public function add_new( $id ) {

        $user = User::where('_id', $id)->first();

        $email = $this->request->has('email') ? $this->request->input('email') : $user->email;
        $fName = $this->request->has('fName') ? $this->request->input('fName') : $user->fName;
        $lName = $this->request->has('lName') ? $this->request->input('lName') : $user->lName;

        $guardian = array(
            'uid' => $id,
            'fNmae' => $fName,
            'lName' => $lName,
            'email' => $email,
        );

        $all = $this->get_all($id);

        if ($all === null) {
            $all = array();
        }

        $all[] = $guardian;

        $g = new Guardians($guardian);
        $u = User::first();
        $g = $u->guardians()->save($guardian);

        var_dump( $g );

        return json_encode( array(
            'success' => true,
            'message' => array(
                'user' => $g,
            )
        ) );
    }

    public function remove_guardian() {

    }

    public function edit_details() {

    }
}