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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type_service')->nullable();
            $table->string('name')->unique();
            $table->string('description');
            $table->unsignedBigInteger('worker_id');
            $table->text('indications')->nullable(); 
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
            //$table->foreignId('worker_id')->constrained('workers')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
