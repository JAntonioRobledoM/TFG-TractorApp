<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMessagesTableMakeReceiverIdNullable extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Primero, elimina la restricción de clave foránea si existe
            if (Schema::hasColumn('messages', 'receiver_id') && 
                Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('messages')) {
                $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('messages');
                foreach ($foreignKeys as $foreignKey) {
                    if (in_array('receiver_id', $foreignKey->getLocalColumns())) {
                        $table->dropForeign($foreignKey->getName());
                    }
                }
            }
            
            // Luego, haz que la columna sea nullable
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
            
            // Finalmente, añade la restricción de clave foránea nuevamente si es necesario
            if (Schema::hasTable('users')) {
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Si necesitas revertir, puedes hacer que la columna no sea nullable
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
        });
    }
}