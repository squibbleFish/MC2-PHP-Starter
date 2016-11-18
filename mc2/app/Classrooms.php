<?php
namespace App;

use Moloquent\Eloquent\Model as Eloquent;

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
}