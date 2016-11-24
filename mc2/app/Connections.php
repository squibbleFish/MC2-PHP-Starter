<?php

namespace App;

use Moloquent\Eloquent\Model as Eloquent;

class Connections extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'connections';

    /**
     * @var string
     */
    protected $primaryKey = '_id';



}