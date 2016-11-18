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
$app->post("{$router}/{$api_v}/login", 'UserController@authenticate_user_pass');

/**
 * User Routes
 */
$app->group( [
        'prefix'    => "{$router}/{$api_v}/user/{id}",
    ],
    function()
    use ( $app ) {

        $app->get( 'details', 'UserController@get_user');

        $app->post( 'edit', 'UserController@edit_user');

        $app->post('password-reset', 'UserController@update_password');

        /**
         * @todo some routes not fully integrated yet.
         */
        $app->post( 'add', function() {
        });

        $app->post( 'authenticate', function() {
        });

        $app->post( 'alpha', function() {
        });

});


/**
 * Guardian Routes
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

/**
 * Children routes
 */
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

/**
 * Classroom routes
 */
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


