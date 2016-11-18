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

/**
 * routes that don't need a prefix
 */
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

        $app->post( 'authenticate', 'UserController@authenticate_user_pass');

        /**
         * @todo some routes not fully integrated yet.
         */
        $app->post( 'add', function() {
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
        'prefix'    => "{$router}/{$api_v}/user/{uid}/children",
    ],
    function()
    use ( $app ) {

        $app->get( 'all', 'ChildrenController@get_all' );

        $app->get( 'details/{id}', 'ChildrenController@get_single' );

        $app->post( 'add', 'ChildrenController@add_child' );

        $app->put( 'edit/{id}', 'ChildrenController@edit_child' );

        $app->delete( 'remove/{id}', 'ChildrenController@delete_child' );
});

/**
 * Classroom routes
 */
$app->group( [
        'prefix'    => "{$router}/{$api_v}/user/{uid}/classroom",
    ],
    function ()
    use ( $app ) {

        $app->get( 'all', 'ClassroomsControllers@get_classrooms' );

        $app->get( 'details/{id}', 'ClassroomsControllers@classroom_details' );

        $app->post( 'add', 'ClassroomsControllers@add_classroom' );

        $app->put( 'edit/{id}', 'ClassroomsControllers@edit_classroom' );

        $app->delete( 'remove/{id}', 'ClassroomsControllers@remove_classroom' );
});

/**
 * catch all for routes
 */
$app->get('/{any:.*}', function () use ($app) {
    return view( '404' );
});