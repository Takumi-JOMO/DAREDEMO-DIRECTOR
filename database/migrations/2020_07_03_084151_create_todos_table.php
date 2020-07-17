<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('todo_name');
            $table->text('image_url')->nullable();
            $table->text('director_comment')->nullable();
            $table->text('customer_comment')->nullable();
            $table->text('designer_programmer_comment')->nullable();
            $table->text('status')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('step_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
