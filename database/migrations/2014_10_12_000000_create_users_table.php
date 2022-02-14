<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('user_role')->default(2)->comment('
                0 = Super Admin,
                1 = Admin,
                2 = Guest
            ');
            $table->rememberToken();
            $table->tinyInteger('user_status')->default(3)->comment('0=inactive, 1=active, 2=Suspended, 3= Pending');
            $table->tinyInteger('user_on_board')->default(0)->comment('0=not in board, 1=Golden board, 2=Diamond board');
            $table->string('otp')->nullable()->default(null);
            $table->dateTime('otp_sent_at')->nullable()->default(null);
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
        Schema::dropIfExists('users');
    }
}
