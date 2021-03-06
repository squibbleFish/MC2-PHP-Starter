<?php

namespace App;

use Moloquent\Eloquent\Model as Eloquent;

/**
 * Class Children
 * @package App
 */
class Children extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'children';

    /**
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->belongsToMany('App\Users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classrooms() {
        return $this->embedsMany('App\Classrooms');
    }

}