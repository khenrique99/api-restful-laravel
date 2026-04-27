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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('brand', 100)->after('name');
            $table->string('model', 100)->after('brand');
            $table->decimal('price', 10, 2)->after('color');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['brand', 'model', 'price', 'user_id']);
        });
    }
};
