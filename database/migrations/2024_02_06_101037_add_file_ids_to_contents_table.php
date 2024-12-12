<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileIdsToContentsTable extends Migration
{
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->string('thumb_file_id')->nullable()->after('thumb_url');
            $table->string('banner_file_id')->nullable()->after('banner_url');
            $table->string('file_file_id')->nullable()->after('file_path');
        });
    }

    public function down()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('thumb_file_id');
            $table->dropColumn('banner_file_id');
            $table->dropColumn('file_file_id');
        });
    }
}
