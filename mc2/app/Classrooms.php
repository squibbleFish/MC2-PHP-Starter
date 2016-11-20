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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('User');
    }
}