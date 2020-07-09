<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('matter_id');
            $table->date('director_schedule_start_date')->nullable();
            $table->date('director_schedule_end_date')->nullable();
            $table->date('customer_schedule_start_date')->nullable();
            $table->date('customer_schedule_end_date')->nullable();
            $table->date('designer_programmer_schedule_start_date')->nullable();
            $table->date('designer_programmer_schedule_end_date')->nullable();
            $table->text('step_name')->nullable();
            $table->text('status')->nullable();
            $table->text('google_drive_url')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('steps');
    }
}
