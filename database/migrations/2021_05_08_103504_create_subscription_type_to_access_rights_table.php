<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTypeToAccessRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_type_to_access_rights', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('subscription_type_id');
            $table->foreign('subscription_type_id')->references('id')->on('subscription_types');
            $table->unsignedBigInteger('access_right_id');
            $table->foreign('access_right_id')->references('id')->on('access_rights');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_type_id');
        Schema::dropIfExists('access_right_id');
        Schema::dropIfExists('subscription_type_to_access_rights');
    }
}
