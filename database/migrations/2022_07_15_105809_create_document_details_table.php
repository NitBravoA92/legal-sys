<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('document_id')->constrained('documents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('orderDetails_id')->constrained('order_details')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_details');
    }
};
