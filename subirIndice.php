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
$driveService = new Google_Service_Drive($client);

foreach ($argv as $arg) {
    $e=explode("=",$arg);
    if(count($e)==2)
        $_GET[$e[0]]=$e[1];
    else    
        $_GET[$e[0]]=0;
}

$ruta=$_GET["ruta"];

$fileMetadata = new Google_Service_Drive_DriveFile(array(
  'name' => 'indiceGenerado',
  'mimeType' => 'application/vnd.google-apps.spreadsheet'));
$content = file_get_contents($ruta);
$file = $driveService->files->create($fileMetadata, array(
  'data' => $content,
  'mimeType' => 'text/html',
  'uploadType' => 'multipart',
  'fields' => 'id'));
printf($file->id);