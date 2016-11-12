<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Guardians extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'guardians';
    protected $primaryKey = '_id';
}