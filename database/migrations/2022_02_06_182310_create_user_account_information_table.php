<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account_information', function (Blueprint $table) {
            $table->id();
            $table->string("user_id");
            $table->tinyInteger("banking_type")->default(1)->comment('1=Mobile Banking, 2=Business Banking');
            $table->string("acc_title")->nullable()->default(null);
            $table->string("ac_no")->nullable()->default(null);
            $table->string("bank_name")->nullable()->default(null);
            $table->string("branch_name")->nullable()->default(null);
            $table->tinyInteger("operator")->nullable()->default(null)->comment('1=bkash, 2=rocket, 3=nagad');
            $table->string("mbl_ac_no")->nullable()->default(null);
            $table->tinyInteger("ac_type")->nullable()->default(null)->comment('1=personal, 2=agent, 3=marchent');
            $table->string("ac_status")->default(1)->comment('1=active, 0=in-active');
            $table->string("created_by")->nullable()->default(null);
            $table->string("updated_by")->nullable()->default(null);
            $table->string("deleted_by")->nullable()->default(null);
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
        Schema::dropIfExists('user_account_information');
    }
}
