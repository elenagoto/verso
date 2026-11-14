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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
            $table->string('type')->default('editor');
        });

        Schema::dropIfExists('types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->foreignId('type_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
