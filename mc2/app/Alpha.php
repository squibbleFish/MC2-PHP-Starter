<?php
namespace App;

use Moloquent\Eloquent\Model as Eloquent;

/**
 * Class Alpha
 * @package App
 */
class Alpha extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'alpha';

    /**
     * @var string
     */
    protected $primaryKey = '_id';
}