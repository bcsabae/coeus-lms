<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('token');
            $table->integer('amount');
            $table->dateTime('valid_from');
            $table->dateTime('valid_until');
            $table->unsignedBigInteger('subscription_type_id');
            $table->foreign('subscription_type_id')->references('id')->on('subscription_types');
            $table->string('card_type')->nullable()->default(null);
            $table->integer('last_four')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
