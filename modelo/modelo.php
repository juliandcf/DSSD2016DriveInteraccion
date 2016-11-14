<?php
require_once('consultas_pdo.php');

function guardar_id_documento($id_trabajo, $id_google_drive){
		$sql = "UPDATE TRABAJO set 	ID_GOOGLE_DRIVE=:id_google_drive WHERE ID_TRABAJO=:id_trabajo";
		$array = array(':id_google_drive' => $id_google_drive, ':id_trabajo' => $id_trabajo);
		consulta_tipo_abm($sql,	$array);

	}

function cambiar_estado($id_drive, $estado){
		$sql = "UPDATE TRABAJO set 	ESTADO_TRABAJO_FK=:estado_trabajo WHERE ID_GOOGLE_DRIVE=:id_google_drive";
		$array = array(':estado_trabajo' => $estado, ':id_google_drive' => $id_drive);
		consulta_tipo_abm($sql,	$array);

	}	

function listar_autores(){
		$sql = "SELECT * FROM AUTOR INNER JOIN TRABAJO WHERE TRABAJO.AUTOR_FK = AUTOR.ID_AUTOR";
		$array = array();
		return consulta_listar_items($sql, $array);;

	}

function get_autor_md5($id_autor_md5){
	$autores=listar_autores();
	$autor_buscado=-1;
	foreach ($autores as $autor) {
		if(md5($autor['ID_AUTOR']) == $id_autor_md5){
			$autor_buscado=$autor;
			break;
		}
	}
	return $autor_buscado;
}			
