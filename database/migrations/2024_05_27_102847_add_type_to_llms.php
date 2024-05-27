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
        Schema::table('llms', function (Blueprint $table) {
            $table->string("type")->default(\App\Domains\Llms\PullStatus::Pending->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('llms', function (Blueprint $table) {
            //
        });
    }
};
