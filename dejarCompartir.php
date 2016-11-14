<?php
require_once __DIR__ . '/vendor/autoload.php';


define('APPLICATION_NAME', 'Drive API PHP Quickstart');
//define('CREDENTIALS_PATH', '~/.credentials/drive-php-quickstart.json');
define('CREDENTIALS_PATH', __DIR__.'/CREDENTIALS_PATH');
define('CLIENT_SECRET_PATH', __DIR__.'/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/drive-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Drive::DRIVE_FILE)
));
//Google_Service_Drive::DRIVE_METADATA_READONLY

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$drive_service = new Google_Service_Drive($client);
$respuesta=array();

//Necesito hacer esto para obtener los parametros pasados por consola y usarlos como si fuera web. Cuando pasas paraemtros por consola estos quedan en un arreglo llamado $argv. Aca itero y los pongo por get. 
//Para llamar a este archivo= 

foreach ($argv as $arg) {
  $e=explode("=",$arg);
  if(count($e)==2)
    $_GET[$e[0]]=$e[1];
  else    
    $_GET[$e[0]]=0;
}

require_once 'modelo/modelo.php';
require_once 'modelo/Estados.php';

$id=$_GET["id"];
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
        $batch->add($drive_service->permissions->delete($id, $perm['id']));
    } 
}
$batch->execute();

$estado = new Estado;

cambiar_estado($id, $estado->getIdEstado('TERMINADO'));

$respuesta['exito']="Permisos borrados correctamente";
echo json_encode($respuesta);