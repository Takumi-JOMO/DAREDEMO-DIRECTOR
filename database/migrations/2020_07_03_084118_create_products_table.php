<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('title');
            $table->text('director_gantt_chart_url')->nullable();
            $table->text('customer_gantt_chart_url')->nullable();
            $table->text('designer_engineer_gantt_chart_url')->nullable();
            $table->unsignedBigInteger('matter_id');
            $table->foreign('matter_id')->references('id')->on('matters')->onDelete('cascade');

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
}
