<?php

namespace App;

use Moloquent\Eloquent\Model as Eloquent;

class Notifcations extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'notifications';

    /**
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany('App\Users');
    }


}