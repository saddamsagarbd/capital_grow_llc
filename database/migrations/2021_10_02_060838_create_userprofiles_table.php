<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('identification_no');
            $table->string('dob');
            $table->string('contact_number')->unique();
            $table->string('profile_img')->nullable()->default(null);
            $table->string('reference_id')->nullable()->default(null);
            $table->string('placement_id')->nullable()->default(null);
            $table->string('generation_id')->nullable()->default(null)->comment('1=1st gen., 2=2nd gen., 3=3rd gen.');
            $table->double('registration_balance', 8, 2)->nullable()->default(null);
            $table->tinyInteger('reference_enable')->default(0)->comment('0=disabled, 1=enable');
            $table->tinyInteger('is_ref_commission_shared')->default(0)->comment('0=disabled, 1=enable');
            $table->dateTime('last_weekly_bonus_earned', $precision = 0)->nullable()->default(null);
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
        Schema::dropIfExists('userprofiles');
    }
}
