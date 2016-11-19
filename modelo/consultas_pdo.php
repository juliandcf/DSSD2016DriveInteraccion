<?php 
	require_once('conexion_bd.php');

	function consulta_tipo_abm($sql, $array){
		try{
			$cn = conectar();
			$cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$cn->beginTransaction();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn->commit();
		}
		catch(PDOException $e){
	   		$cn->rollBack();
	   		return $e->getCode();
		}
		finally{
		$cn = desconectar();
		}
	}

	function consulta_listar_items($sql, $array){
		try{
			$cn = conectar();
			$cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$cn->beginTransaction();
			$query = $cn->prepare($sql);
			$query->execute($array);
			$cn->commit();
			return $query->fetchAll();
		}
		catch(PDOException $e){
			$cn->rollBack();
	   		return $e->getCode();
		}finally{
			$cn = desconectar();
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