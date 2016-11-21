<?php
namespace App;

use Moloquent\Eloquent\Model as Eloquent;

use App\User;
use App\Children;

/**
 * Class Classrooms
 * @package App
 */
class Classrooms extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'classrooms';

    /**
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany('App\Children');
    }
}