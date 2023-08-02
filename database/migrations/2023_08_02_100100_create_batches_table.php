<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_id');
            $table->date('batch_date');
            $table->string('batch_ref_num');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->unique(['merchant_id', 'batch_date', 'batch_ref_num']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batches');
    }
}
