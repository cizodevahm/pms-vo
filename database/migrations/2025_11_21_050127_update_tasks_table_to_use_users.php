<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['team_member_id']);

            // Rename the column to user_id
            $table->renameColumn('team_member_id', 'user_id');

            // Add foreign key constraint to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);

            // Rename the column back to team_member_id
            $table->renameColumn('user_id', 'team_member_id');

            // Add foreign key constraint back to team_members table
            $table->foreign('team_member_id')->references('id')->on('team_members')->onDelete('cascade');
        });
    }
};
