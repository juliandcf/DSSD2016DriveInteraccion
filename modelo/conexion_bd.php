<?php
	function conectar(){
		try {
			return new PDO("mysql:dbname=inscripcion_congreso;host=localhost","dssd","dssd");
		}
		catch(PDOException $e){
			return $e->getCode();
		}
	}

	function desconectar(){
		return NULL;
	}
?>