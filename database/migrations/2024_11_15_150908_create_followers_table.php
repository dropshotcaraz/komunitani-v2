<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('followers', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained()->after('id');
        $table->foreignId('follower_id')->constrained('users')->after('user_id');
    });
}

public function down()
{
    Schema::table('followers', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
        $table->dropForeign(['follower_id']);
        $table->dropColumn('follower_id');
    });
}
};
