<?php
require_once 'google-api-php-client/autoload.php';
require_once '../modelo/modelo.php';
require_once '../modelo/Estados.php';
ini_set('display_errors', 'On');
session_start();
$respuesta=array();
if (isset($_SESSION['access_token'])){

    $id=$_POST["id_drive"];
    $client = new Google_Client();
    $client->setAccessToken($_SESSION['access_token']);
    $drive_service = new Google_Service_Drive($client);
    $optParams = array(  
     'fields' => 'owners(permissionId), permissions(id)'
     );
    $archivo = $drive_service->files->get($id, $optParams);
    $permiso_propietario=($archivo->owners)[0]['permissionId'];
    
    $permisos_a_borrar=[];
    $permisos_archivo=$archivo->getPermissions();
    $drive_service->getClient()->setUseBatch(true);
    $batch = $drive_service->createBatch();
    foreach ($permisos_archivo as $perm) {
      if($perm['id'] != $permiso_propietario){
        array_push($permisos_a_borrar, $perm['id']);
        $perm->setRole("reader");
        $batch->add($drive_service->permissions->update($id, $perm['id'], $perm));
    } 
}
$batch->execute();

$estado = new Estado;

cambiar_estado($id, $estado->getIdEstado('FINALIZADO'));

$respuesta['exito']="Permisos cambiados correctamente";
} else {
 $respuesta['error']="No se pudieron cambiar los permisos";
}
echo json_encode($respuesta);

?>