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
        $this->schema::table('users', function (Blueprint $table) {
           $table->addColumn('string', 'name', ['length' => 255, 'required' => true]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
