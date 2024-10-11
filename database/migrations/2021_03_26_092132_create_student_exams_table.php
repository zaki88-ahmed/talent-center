<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();

            $table -> bigInteger('exam_id')->unsigned();
            $table -> foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');


            $table -> double('total_degree');

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
        Schema::dropIfExists('student_exams');
    }
}
