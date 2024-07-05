<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION set_orden()
            RETURNS TRIGGER AS $$
            BEGIN
            DECLARE
                ultimo_orden INTEGER;
            BEGIN
                SELECT COUNT(usuario_id)
                INTO ultimo_orden
                FROM articulos_autores
                WHERE evento_id = NEW.evento_id AND articulo_id = NEW.articulo_id;

                NEW.orden := ultimo_orden + 1;

                RETURN NEW;
            END;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER set_orden_autor_articulos_before
            BEFORE INSERT ON articulos_autores
            FOR EACH ROW
            EXECUTE PROCEDURE set_orden();
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION articulo_estado()
            RETURNS TRIGGER AS $BODY$
            DECLARE
            articulo INT;
            evento INT;
            BEGIN
            articulo := NEW.articulo_id;
            evento:= NEW.evento_id;

            UPDATE ARTICULOS
            SET estado = \'En revision\'
            WHERE ARTICULOS.evento_id = evento AND ARTICULOS.id = articulo;

            RETURN NEW;
            END;
            $BODY$
            LANGUAGE plpgsql VOLATILE COST 100;
        ');
        DB::unprepared('
            CREATE TRIGGER update_articulo_estado
            AFTER INSERT ON revisores_articulos
            FOR EACH ROW
            EXECUTE PROCEDURE articulo_estado();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_orden_autor_articulos_before ON articulos_autores');
        DB::unprepared('DROP FUNCTION IF EXISTS set_orden()');
        DB::unprepared('DROP TRIGGER IF EXISTS update_articulo_estado ON revisores_articulos');
        DB::unprepared('DROP FUNCTION IF EXISTS articulo_estado()');
    }
};
