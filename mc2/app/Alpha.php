<?php
namespace App;

//use Jenssegers\Mongodb\Eloquent\Model as Model;
use Moloquent\Eloquent\Model as Eloquent;

/**
 * Class Alpha
 * @package App
 */
class Alpha extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'alpha';
    protected $primaryKey = '_id';
}