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
        Schema::create('order_notif_files_attaches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('order_notifications_id')->constrained('order_notifications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('documents')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_notif_files_attaches');
    }
};
