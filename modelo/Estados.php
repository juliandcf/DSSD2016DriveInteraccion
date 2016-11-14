<?php
ini_set('display_errors', 'On');

class Estado{
var $estados = ["INSCRIPTO" => 1,
		   "ASIGNADO" => 2, 
		   "APROBADO" => 3,
		   "DESAPROBADO" => 4,
		   "FINALIZADO" => 5, 
		   "TERMINADO" => 6];


	function getIdEstado($string){
		return $this->estados[$string];
	}
}    
?>