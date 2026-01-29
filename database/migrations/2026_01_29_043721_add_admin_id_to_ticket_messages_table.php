<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->uuid('admin_id')->nullable()->after('user_id');
            $table->uuid('user_id')->nullable()->change();
            
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
            $table->uuid('user_id')->nullable(false)->change();
        });
    }
};