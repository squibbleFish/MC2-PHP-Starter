<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint as Mongo;

class UsersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Mongo $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unique('email');
            $table->char('tempPassword', 255);
            $table->char('password', 255);
            $table->char('fName', 255);
            $table->char('lName', 255);
            $table->integer('age');
            $table->json('location');
            $table->json('children');
            $table->char('relationship', 255);
            $table->char('education', 255);
            $table->char('religion', 255);
            $table->char('maritalStatus', 255);
            $table->json('languages');
            $table->boolean('host');
            $table->boolean('backgroundCheck');
            $table->json('classrooms');
            $table->json('notifications');
            $table->float('ratings');
            $table->float('reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
