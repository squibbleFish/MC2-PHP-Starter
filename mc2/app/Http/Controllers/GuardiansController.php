<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Mail;

use App\User;
use App\Guardians;
use App\Children;
use App\Classrooms;
use App\Notifcations;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

Use Log;

/**
 * @todo Need profile picture added
 */

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

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function get_all( $uid, $id ) {
        return User::select('*')->where('_id', $id )->first()->guardians;
    }

    /**
     * @param $uid
     * @param $id
     * @return mixed
     */
    public function get_single( $uid, $id ) {
        return Guardians::where([
            ['user_id', $uid],
            ['_id', $id]
        ])->first();
    }

    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function edit_details( $uid, $id ) {
        $g = User::where('_id', $uid)->first()->guardians()->where('_id', $id)->first();
        $g->email = $this->request->has('email') ? $this->request->input('email') : $g->email;
        $g->fName = $this->request->has('fName') ? $this->request->input('fName') : $g->fName;
        $g->lName = $this->request->has('lName') ? $this->request->input('lName') : $g->lName;

        try {
            $g->update();
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
                'guardians' => $g,
            )
        ) );

    }

    /**
     * @todo Verification email and new user should be added as part of this method
     * @todo we also need a to update the notification center
     *
     * @param $id
     * @return string
     */
    public function add_new( $uid ) {
        $g = new Guardians();
        $u = User::where('_id', $uid )->first();

        $g->email = $this->request->has('email') ? $this->request->input('email') : $g->email;
        $g->fName = $this->request->has('fName') ? $this->request->input('fName') : $g->fName;
        $g->lName = $this->request->has('lName') ? $this->request->input('lName') : $g->lName;
        $g->uid = $uid;

        try {
            $g = $u->guardians()->save($g);
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
                'users'     => $u,
                'guardians' => $g,
            )
        ) );
    }

    /**
     * @param $uid
     * @param $id
     * @return string
     */
    public function remove_guardian( $uid, $id ) {
        $g = User::where('_id', $uid)->first()->guardians()->where('_id', $id)->first();

        try {
            $g->delete();
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
                'guardians' => $g,
            )
        ) );

    }

}