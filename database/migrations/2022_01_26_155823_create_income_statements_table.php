<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_statements', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('account_no');
            $table->string('trxn_id');
            $table->string('trxn_details');
            $table->string('debit')->nullable()->default(null);
            $table->string('credit')->nullable()->default(null);
            $table->string('balance');
            $table->tinyInteger('is_weekly_bonus')->default(0)->comment('0=general income, 1=weekly income');
            $table->string('created_by')->nullable()->default(null);
            $table->string('updated_by')->nullable()->default(null);
            $table->string('deleted_by')->nullable()->default(null);
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
        Schema::dropIfExists('income_statements');
    }
}
