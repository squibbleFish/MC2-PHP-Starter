<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api_v = 'v1';
$router = 'api';

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post("{$router}/{$api_v}/alpha-codes", 'UserController@alpha_hack');
$app->get("{$router}/{$api_v}/user-confirm", 'UserController@user_confirm');


/**
 * User Methods
 */
$app->group( [
        'prefix'    => "{$router}/{$api_v}/user/{id}",
    ],
    function()
    use ( $app ) {

        $app->get( 'details', function( $id ) {
            return 'Gets current user';
        });

        $app->put( 'edit', function( $id ) {
            var_dump( $id );
        });

        $app->post( 'add', function() {
            return 'New User';
        });

        $app->post( 'authenticate', function( $id ) {

        });

        $app->post( 'alpha', function() {
            return "Code to Hit";
        });

});


/**
 * Guardian Methods
 */
$app->group( [
        'prefix'    => "{$router}/{$api_v}/guardian/{id}",
    ],
    function ()
    use ( $app ) {

        $app->get( 'all', 'GuardiansController@get_all');


        $app->get( 'details', 'GuardiansController@get_single');


        $app->post( 'add', 'GuardiansController@add_new');

        $app->put( 'edit', 'GuardiansController@edit_details');

        $app->delete( 'remove', 'GuardiansController@remove_guardian');

});

$app->group([
        'prefix'    => "{$router}/{$api_v}/children/{id}",
    ],
    function()
    use ( $app ) {

        $app->get( 'all', function () {

        });

        $app->get( 'details', function ($id) {

        });

        $app->post( 'add', function() {

        });

        $app->put( 'edit', function ($id) {

        });


        $app->delete( 'remove', function() {

        });
});

$app->group( [
        'prefix'    => "{$router}/{$api_v}/classroom/{id}",
    ],
    function ()
    use ( $app ) {

        $app->get( 'all', function () {

        });

        $app->get( 'details', function ($id) {

        });

        $app->post( 'add', function() {

        });

        $app->put( 'edit', function ($id) {

        });


        $app->delete( 'remove', function() {

        });
});


