<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePizzaSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pizza_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pizza_id')->constrained();
            $table->unsignedTinyInteger('size')->comment('Diameter in inches.');
            $table->unsignedInteger('price_usd')->comment('Price in US$ times 100.');
            $table->unsignedInteger('price_eur')->comment('Price in Euros times 100.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pizza_sizes');
    }
}
