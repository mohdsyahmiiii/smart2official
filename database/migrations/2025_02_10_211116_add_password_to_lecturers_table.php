<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordToLecturersTable extends Migration
{
    public function up()
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('password')->after('email');
        });
    }

    public function down()
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
}