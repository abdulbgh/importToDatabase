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
        Schema::create('buffers', function (Blueprint $table) {
            $table->id();
            $table->integer('imported_by');
            $table->integer('module_id');
            $table->integer('row_no');
            $table->string('meta_data');
            $table->string('validation_status');
            $table->string('message');
            $table->string('import_status');
            $table->string('document_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffer');
    }
};
