<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['creator_by_id']);
            $table->renameColumn('creator_by_id', 'created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->renameColumn('created_by_id', 'creator_by_id');
            $table->foreign('creator_by_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
