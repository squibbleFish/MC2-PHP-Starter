<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

/**
 * Class Alpha
 * @package App
 */
class Alpha extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'alpha';
    protected $primaryKey = '_id';
}