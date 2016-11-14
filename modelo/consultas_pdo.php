<?php 
	require_once('conexion_bd.php');

	function consulta_tipo_abm($sql, $array){
		try{
			$cn = conectar();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn = desconectar();
		}
		catch(PDOException $e){
	   		return $e->getCode();
		}
	}

	function consulta_alta_con_relacionados($sql, $array, $nombre_tabla){
		try{
			$cn = conectar();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$ultimo_id = $cn->lastInsertId($nombre_tabla); 
			$cn = desconectar();
			return $ultimo_id;
		}
		catch(PDOException $e){
	   		return $e->getCode();
		}

	}

	function consulta_listar_items($sql, $array){
		try{
			$cn = conectar();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn = desconectar();
			return $query->fetchAll();
		}
		catch(PDOException $e){
	   		return $e->getCode();
		}
	}

	function consulta_buscar_item($sql, $array){
		try{
			$cn = conectar();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn = desconectar();
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
	   		return $e->getCode();
		}
	}

	function existe_item($sql, $array){
		try{
			$cn = conectar();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn = desconectar();
			return ($query->rowCount() > 0);
		}
		catch(PDOException $e){
	   		return $e->getCode();
		}
	}
?>