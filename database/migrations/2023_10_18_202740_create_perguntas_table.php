<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tema_id');
            $table->unsignedBigInteger('user_id');
            $table->string('pergunta', 5000);
            $table->tinyInteger('pergunta_sugerida')->defalt(0);
            $table->tinyInteger('pergunta_estado')->defalt(0);
            $table->tinyInteger('pergunta_atualizacao')->defalt(0);
            $table->timestamps();

            $table->foreign('tema_id')->references('id')->on('temas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    //BANCO
//alterando pra null, na refatoracao reavaliar
//alter table perguntas MODIFY COLUMN `tema_id` bigint unsigned DEFAULT NULL

// --alterando tipo de dado e setando null, depois de refatorar o codigo no back, alterar pra not null e bool
// alter table perguntas MODIFY COLUMN `pergunta_sugerida` int NULL default null

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perguntas');
    }
}
