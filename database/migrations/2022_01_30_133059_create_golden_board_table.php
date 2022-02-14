<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoldenBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golden_board', function (Blueprint $table) {
            $table->id();
            $table->string("user_id");
            $table->string("queue_count");
            $table->tinyInteger("is_bonus_shared")->default(0)->comment('0=not shared, 1=shareds');
            $table->tinyInteger("is_switched_to_diamond_board")->default(0)->comment('0=not switched, 1=switched');
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
        Schema::dropIfExists('golden_board');
    }
}
