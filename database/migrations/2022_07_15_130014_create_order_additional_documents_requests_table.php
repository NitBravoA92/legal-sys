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
        Schema::create('order_additional_documents_requests', function (Blueprint $table) {
            $table->id();
            $table->string('fieldname_english');
            $table->string('fieldname_spanish');
            $table->string('field_type');
            $table->string('status');
            $table->timestamps();
            $table->foreignId('order_id')->constrained('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onUpdate('cascade')->onDelete('cascade');		
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_additional_documents_requests');
    }
};
