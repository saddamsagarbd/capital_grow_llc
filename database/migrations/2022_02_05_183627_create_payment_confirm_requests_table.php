<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentConfirmRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_confirm_requests', function (Blueprint $table) {
            $table->id();
            $table->string("user_id");
            $table->string("username");
            $table->string("transaction_no");
            $table->tinyInteger("pay_to")->default(1)->comment('1=bkash, 2=rocket, 3=nagad, 4=bank deposite');
            $table->string("pay_to_ac");
            $table->string("paid_amount");
            $table->dateTime("payment_date");
            $table->string("receipt_name")->nullable()->default(null);
            $table->tinyInteger("payment_request")->default(0)->comment('0=pending, 1=confirm');
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
        Schema::dropIfExists('payment_confirm_requests');
    }
}
