<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentIntentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('uid')->nullable()->default(null);
            $table->string('vendor')->nullable()->default(null);
            $table->float('price');
            $table->dateTime('last_attempt')->nullable();
            $table->dateTime('next_attempt')->nullable();
            $table->smallInteger('remaining_retries')->nullable()->default(null);
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_intents');
    }
}
