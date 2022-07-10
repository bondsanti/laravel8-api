<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 250);
            $table->string('lastname', 250);
            $table->decimal('salary', 10, 2); //8 หลัก ทศนิยม 2 ตำแหน่ง
            $table->date('dob')->nullable();
            $table->boolean('is_active')->default(false); // 0
            $table->string('picture')->default('nopic.png');
            $table->index('firstname');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreignId('user_id')->constrained('users'); //users table ต้องมีคอลัมน์ id เป็นprimary
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officers');
    }
}
