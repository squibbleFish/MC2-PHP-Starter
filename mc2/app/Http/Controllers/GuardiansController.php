<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Guardians;

/**
 * Class GuardianController
 * @package App\Http\Controllers
 */
class GuardiansController extends Controller
{

    protected $request;

    /**
     * GuardianController constructor.
     */
    public function __construct( Request $request ) {
        $this->request = $request;
    }

    public function get_all( $id ) {

        return Guardians::get();
    }

    public function get_single( $id ) {
        return Guardians::get();
    }

    public function add_new() {

    }

    public function remove_guardian() {

    }

    public function edit_details() {

    }
}