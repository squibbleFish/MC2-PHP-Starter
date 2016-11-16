<?php
namespace App;

//use Jenssegers\Mongodb\Eloquent\Model as Model;
use Moloquent\Eloquent\Model as Eloquent;

class Guardians extends Eloquent
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'guardians';

    /**
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'fName', 'lName', 'uid'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->embedsOne('App\User');
    }

}