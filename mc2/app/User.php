<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Jenssegers\Mongodb\Eloquent\Model as Model;
use Moloquent\Eloquent\Model as Eloquent;


class User extends Eloquent implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'fName', 'lName', 'uid'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardians() {
        return $this->embedsMany( 'App\Guardians' );
    }
}
