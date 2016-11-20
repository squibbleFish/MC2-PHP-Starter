<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Moloquent\Eloquent\Model as Eloquent;

use App\Classrooms;

/**
 * Class User
 * @package App
 */
class User extends Eloquent implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'users';

    /**
     * @var string
     */
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

    /**
     * @return mixed
     */
    public function classrooms() {
        return $this->hasMany( 'Classrooms' );
//        return $this->hasMany( 'Classrooms' )->where('users._id', '=', 'classrooms._id');
    }
}
