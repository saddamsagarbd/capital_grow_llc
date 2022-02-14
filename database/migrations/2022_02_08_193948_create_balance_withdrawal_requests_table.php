<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceWithdrawalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->string("user_id");
            $table->string("withdrawal_amount");
            $table->string("request_status")->default(2)->comment('0=declined, 1=paid, 2=pending');
            $table->string("created_by")->nullable()->default(null);
            $table->string("updated_by")->nullable()->default(null);
            $table->string("declined_by")->nullable()->default(null);
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
        Schema::dropIfExists('balance_withdrawal_requests');
    }
}
