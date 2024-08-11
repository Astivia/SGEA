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
        //aumatizar orden de Autores
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
        //actualizar estado del articulo (En revision)
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

        //convertir usuario en  autor
        DB::unprepared("
            CREATE OR REPLACE FUNCTION convertirEnAutor() RETURNS TRIGGER AS $$
            BEGIN
            INSERT INTO PARTICIPANTES (evento_id, usuario_id, rol)
            VALUES (NEW.evento_id, NEW.usuario_id, 'Autor')
            ON CONFLICT (evento_id, usuario_id) DO UPDATE SET rol =rol ||'- '|| EXCLUDED.rol;
            RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

        ");
        DB::unprepared('
            CREATE TRIGGER insertar_autor_participante
            AFTER INSERT ON ARTICULOS_AUTORES
            FOR EACH ROW
            EXECUTE PROCEDURE convertirEnAutor();
        ');
        //convertir usuario en  revisor
        DB::unprepared("
            CREATE OR REPLACE FUNCTION convertirEnRevisor() RETURNS TRIGGER AS $$
            BEGIN
            INSERT INTO PARTICIPANTES (evento_id, usuario_id, rol)
            VALUES (NEW.evento_id, NEW.usuario_id, 'Revisor')
            ON CONFLICT (evento_id, usuario_id) DO UPDATE SET rol =rol ||'- '|| EXCLUDED.rol;
            RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

        ");
        DB::unprepared('
            CREATE TRIGGER insertar_revisor_participante
            AFTER INSERT ON REVISORES_ARTICULOS
            FOR EACH ROW
            EXECUTE PROCEDURE convertirEnRevisor();
        ');
        //Funciones de migracion
        DB::unprepared("
            CREATE OR REPLACE FUNCTION migrar_datos()
            RETURNS void AS $$
            BEGIN
                BEGIN
                    -- Migrar datos nuevos de la tabla AREAS
                    INSERT INTO public.areas_history (id, nombre, descripcion)
                    SELECT a.id, a.nombre, a.descripcion 
                    FROM public.areas a
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.areas_history ah
                        WHERE a.id = ah.id
                    );
                    -- Migrar datos nuevos de la tabla USUARIOS
                    INSERT INTO public.usuarios_history (id, curp, foto, nombre, ap_paterno, ap_materno, email, password, telefono, estado)
                    SELECT u.id, u.curp, u.foto, u.nombre, u.ap_paterno, u.ap_materno, u.email, u.password, u.telefono, u.estado 
                    FROM public.usuarios u
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.usuarios_history uh
                        WHERE u.id = uh.id
                    );
                    -- Migrar datos de la tabla EVENTOS
                    INSERT INTO public.eventos_history (id, logo, nombre, acronimo, fecha_inicio, fecha_fin, edicion, estado)
                    SELECT e.id, e.logo, e.nombre, e.acronimo, e.fecha_inicio, e.fecha_fin, e.edicion, e.estado
                    FROM public.eventos e
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.eventos_history eh
                        WHERE e.id = eh.id
                    );
                    -- Migrar datos de la tabla ARTICULOS
                    INSERT INTO public.articulos_history (evento_id, id, titulo, resumen, archivo, area_id, estado)
                    SELECT a.evento_id, a.id, a.titulo, a.resumen, a.archivo, a.area_id, a.estado
                    FROM public.articulos a
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.articulos_history ah
                        WHERE a.id = ah.id
                    );
                    -- Migrar datos de la tabla REVISORES_ARTICULOS
                    INSERT INTO public.revisores_articulos_history (evento_id, articulo_id, usuario_id, orden, notificado, puntuacion, similitud, comentarios)
                    SELECT ra.evento_id, ra.articulo_id, ra.usuario_id, ra.orden, ra.notificado, ra.puntuacion, ra.similitud, ra.comentarios
                    FROM public.revisores_articulos ra
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.revisores_articulos_history rah
                        WHERE ra.articulo_id = rah.articulo_id AND ra.usuario_id = rah.usuario_id
                    );
                    -- Migrar datos de la tabla ARTICULOS_AUTORES
                    INSERT INTO public.articulos_autores_history (evento_id, articulo_id, usuario_id, orden, correspondencia, institucion, email)
                    SELECT aa.evento_id, aa.articulo_id, aa.usuario_id, aa.orden, aa.correspondencia, aa.institucion, aa.email
                    FROM public.articulos_autores aa
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.articulos_autores_history aah
                        WHERE aa.articulo_id = aah.articulo_id AND aa.usuario_id = aah.usuario_id
                    );

                    -- Migrar datos de la tabla PARTICIPANTES
                    INSERT INTO public.participantes_history (evento_id, usuario_id, rol)
                    SELECT p.evento_id, p.usuario_id, p.rol
                    FROM public.participantes p
                    WHERE NOT EXISTS (
                        SELECT 1 FROM public.participantes_history ph
                        WHERE p.evento_id = ph.evento_id AND p.usuario_id = ph.usuario_id
                    );

                    -- Eliminar datos originales
                    DELETE FROM public.participantes;
                    DELETE FROM public.revisores_articulos;
                    DELETE FROM public.articulos_autores;
                    DELETE FROM public.articulos;
                    DELETE FROM public.eventos;

                    EXCEPTION
                    WHEN OTHERS THEN
                        -- Revertir transacción en caso de error
                        ROLLBACK;
                        RAISE;
                END;
            END;
            $$ LANGUAGE plpgsql;

        ");
        DB::unprepared("
            CREATE OR REPLACE FUNCTION migrar_datosPorEvento(eventoID INT)
            RETURNS void AS $$
            BEGIN
                -- Migrar datos nuevos de la tabla AREAS
                INSERT INTO public.areas_history (id, nombre, descripcion)
                SELECT a.id, a.nombre, a.descripcion 
                FROM public.areas a
                WHERE NOT EXISTS (
                    SELECT 1 FROM public.areas_history ah
                    WHERE a.id = ah.id
                );

                -- Migrar datos nuevos de la tabla USUARIOS
                INSERT INTO public.usuarios_history (id, curp, foto, nombre, ap_paterno, ap_materno, email, password, telefono, estado)
                SELECT u.id, u.curp, u.foto, u.nombre, u.ap_paterno, u.ap_materno, u.email, u.password, u.telefono, u.estado 
                FROM public.usuarios u
                WHERE NOT EXISTS (
                    SELECT 1 FROM public.usuarios_history uh
                    WHERE u.id = uh.id
                );

                -- Migrar datos de la tabla EVENTOS
                INSERT INTO public.eventos_history (id, logo, nombre, acronimo, fecha_inicio, fecha_fin, edicion, estado)
                SELECT e.id, e.logo, e.nombre, e.acronimo, e.fecha_inicio, e.fecha_fin, e.edicion, e.estado
                FROM public.eventos e
                WHERE e.id = eventoID
                AND NOT EXISTS (
                    SELECT 1 FROM public.eventos_history eh
                    WHERE e.id = eh.id
                );

                -- Migrar datos de la tabla ARTICULOS 
                INSERT INTO public.articulos_history (evento_id, id, titulo, resumen, archivo, area_id, estado)
                SELECT a.evento_id, a.id, a.titulo, a.resumen, a.archivo, a.area_id, a.estado
                FROM public.articulos a
                WHERE a.evento_id = eventoID
                AND NOT EXISTS (
                    SELECT 1 FROM public.articulos_history ah
                    WHERE a.id = ah.id
                );

                -- Migrar datos de la tabla REVISORES_ARTICULOS 
                INSERT INTO public.revisores_articulos_history (evento_id, articulo_id, usuario_id, orden, notificado, puntuacion, similitud, comentarios)
                SELECT ra.evento_id, ra.articulo_id, ra.usuario_id, ra.orden, ra.notificado, ra.puntuacion, ra.similitud, ra.comentarios
                FROM public.revisores_articulos ra
                WHERE ra.evento_id = eventoID
                AND NOT EXISTS (
                    SELECT 1 FROM public.revisores_articulos_history rah
                    WHERE ra.articulo_id = rah.articulo_id AND ra.usuario_id = rah.usuario_id
                );

                -- Migrar datos de la tabla ARTICULOS_AUTORES 
                INSERT INTO public.articulos_autores_history (evento_id, articulo_id, usuario_id, orden, correspondencia, institucion, email)
                SELECT aa.evento_id, aa.articulo_id, aa.usuario_id, aa.orden, aa.correspondencia, aa.institucion, aa.email
                FROM public.articulos_autores aa
                WHERE aa.evento_id = eventoID
                AND NOT EXISTS (
                    SELECT 1 FROM public.articulos_autores_history aah
                    WHERE aa.articulo_id = aah.articulo_id AND aa.usuario_id = aah.usuario_id
                );

                -- Migrar datos de la tabla PARTICIPANTES 
                INSERT INTO public.participantes_history (evento_id, usuario_id, rol)
                SELECT p.evento_id, p.usuario_id, p.rol
                FROM public.participantes p
                WHERE p.evento_id = eventoID
                AND NOT EXISTS (
                    SELECT 1 FROM public.participantes_history ph
                    WHERE p.evento_id = ph.evento_id AND p.usuario_id = ph.usuario_id
                );

                -- Eliminar datos originales 
                DELETE FROM public.participantes WHERE evento_id = eventoID;
                DELETE FROM public.revisores_articulos WHERE evento_id = eventoID;
                DELETE FROM public.articulos_autores WHERE evento_id = eventoID;
                DELETE FROM public.articulos WHERE evento_id = eventoID;
                DELETE FROM public.eventos WHERE id = eventoID;
            END;
            $$ LANGUAGE plpgsql;
        ");
        //Mnejo de fechas y estado del evento
        DB::unprepared("
            CREATE OR REPLACE FUNCTION actualizar_estado_evento()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.estado = 4 THEN
                    RETURN NEW;
                END IF;
                
                -- Si la fecha de inicio es igual a la fecha del sistema
                IF NEW.fecha_inicio = CURRENT_DATE THEN
                    NEW.estado := 2;
                -- Si la fecha de inicio es posterior a la fecha del sistema
                ELSIF NEW.fecha_inicio > CURRENT_DATE THEN
                    NEW.estado := 1;
                -- Si la fecha de fin es posterior a la fecha del sistema
                ELSIF NEW.fecha_fin < CURRENT_DATE THEN
                    NEW.estado := 3;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");
        DB::unprepared ("
            CREATE TRIGGER trg_actualizar_estado_evento
            BEFORE INSERT OR UPDATE ON eventos
            FOR EACH ROW
            EXECUTE PROCEDURE actualizar_estado_evento();
        ");
        //Actualizar estado del articulo (Con Observaciones)
        DB::unprepared ("
            CREATE OR REPLACE FUNCTION update_estado()
            RETURNS TRIGGER AS $$
            DECLARE
            v_articulo_id INT := NEW.articulo_id;
            v_evento_id INT := NEW.evento_id;
            v_total_revisores INT;
            v_revisores_evaluados INT;
            BEGIN
                SELECT COUNT(*) 
                INTO v_total_revisores
                FROM revisores_articulos
                WHERE articulo_id = v_articulo_id AND evento_id = v_evento_id;

                SELECT COUNT(*) 
                INTO v_revisores_evaluados
                FROM revisores_articulos
                WHERE articulo_id = v_articulo_id AND evento_id = v_evento_id AND puntuacion IS NOT NULL;


                IF v_revisores_evaluados = v_total_revisores THEN
                    BEGIN
                    -- Actualizar el estado del artículo
                    UPDATE articulos
                    SET estado = 'Con Observaciones'
                    WHERE id = v_articulo_id AND evento_id = v_evento_id;
                    EXCEPTION
                    WHEN OTHERS THEN
                        RAISE NOTICE 'Error al actualizar el estado del artículo: %', SQLERRM;
                    END;
                END IF;

                RETURN NEW;
                EXCEPTION
                WHEN OTHERS THEN
                    RAISE EXCEPTION 'Error en la función update_estado: %', SQLERRM;
            END;
            $$ LANGUAGE plpgsql VOLATILE COST 100;
        ");
        DB::unprepared ("
            CREATE TRIGGER update_estado_Observaciones
            AFTER UPDATE ON revisores_articulos
            FOR EACH ROW
            EXECUTE PROCEDURE update_estado();
        ");

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
        DB::unprepared('DROP TRIGGER IF EXISTS insertar_autor_participante');
        DB::unprepared('DROP FUNCTION IF EXISTS convertirEnAutor() ');
        DB::unprepared('DROP TRIGGER IF EXISTS insertar_revisor_participante');
        DB::unprepared('DROP FUNCTION IF EXISTS convertirEnRevisor()');
        DB::unprepared('DROP FUNCTION IF EXISTS migrar_datos()');
        DB::unprepared('DROP FUNCTION IF EXISTS migrar_datosPorEvento(integer)');
        DB::unprepared('DROP TRIGGER IF EXISTS update_estado_Observaciones ON revisores_articulos');
        DB::unprepared('DROP FUNCTION IF EXISTS update_estado()');
    }
};
