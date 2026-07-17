<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->string('category', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->string('category', 255)->change();
        });
    }
};
