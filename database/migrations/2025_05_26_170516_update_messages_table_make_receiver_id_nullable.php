<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateMessagesTableMakeReceiverIdNullable extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Intentar eliminar la clave foránea si existe
            try {
                // Identificar el nombre de la clave foránea (que puede variar según la convención de nombres)
                $foreignKeyName = $this->getForeignKeyName('messages', 'receiver_id');
                
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                }
            } catch (\Exception $e) {
                // Si falla, continuamos con la migración
            }
            
            // Hacer que receiver_id sea nullable
            $table->unsignedBigInteger('receiver_id')->nullable()->change();
            
            // Añadir la clave foránea nuevamente si la tabla users existe
            if (Schema::hasTable('users')) {
                $table->foreign('receiver_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Intentar eliminar la clave foránea si existe
            try {
                // Identificar el nombre de la clave foránea
                $foreignKeyName = $this->getForeignKeyName('messages', 'receiver_id');
                
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                }
            } catch (\Exception $e) {
                // Si falla, continuamos con la migración
            }
            
            // Hacer que receiver_id no sea nullable
            $table->unsignedBigInteger('receiver_id')->nullable(false)->change();
            
            // Añadir la clave foránea nuevamente si la tabla users existe
            if (Schema::hasTable('users')) {
                $table->foreign('receiver_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Obtiene el nombre de la clave foránea para una columna específica.
     *
     * @param string $table
     * @param string $column
     * @return string|null
     */
    private function getForeignKeyName($table, $column)
    {
        // Método compatible con Laravel 11/12 para obtener las claves foráneas
        $foreignKeys = Schema::getForeignKeys($table);
        
        foreach ($foreignKeys as $foreignKey) {
            if (in_array($column, $foreignKey['columns'])) {
                return $foreignKey['name'];
            }
        }
        
        return null;
    }
}