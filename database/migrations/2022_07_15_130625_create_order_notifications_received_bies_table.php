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
        Schema::create('order_notifications_received_bies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('order_notifications_id')->constrained('order_notifications')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('order_notifications_received_bies');
    }
};
