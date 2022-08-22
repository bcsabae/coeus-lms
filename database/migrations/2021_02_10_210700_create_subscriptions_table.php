<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->dateTime('trial_start')->nullable()->default(null);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('status')->nullable()->default(null);

            $table->dateTime('recurring_ends')->nullable()->default(null);

            $table->unsignedBigInteger('subscription_types_id');
            $table->foreign('subscription_types_id')->references('id')->on('subscription_types');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
