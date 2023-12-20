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
        Schema::create('product_forms', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('product_id');
            $table->string('fieldname_english');
            $table->string('fieldname_spanish');
            $table->string('field_type');
            $table->timestamps();

            $table->foreignId('product_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_forms');
    }
};
