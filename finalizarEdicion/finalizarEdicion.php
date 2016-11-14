<?php
require_once 'google-api-php-client/autoload.php';
//ini_set('display_errors', 'On');

session_start();
$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/DSSD/finalizarEdicion/finalizarEdicion.php');
$client->addScope("https://www.googleapis.com/auth/drive");
if (!isset($_GET['code'])) {
	$_SESSION['id']=$_GET['id'];
	$auth_url = $client->createAuthUrl();
	header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
	require_once '../modelo/modelo.php';
	require_once('vista/cargar_template_twig.php');
	$client->authenticate($_GET['code']);
	$_SESSION['access_token'] = $client->getAccessToken();
	session_start();
	$id_autor=$_SESSION['id'];
	$drive_service = new Google_Service_Drive($client);
        $optParams = array(  
         'fields' => 'user(emailAddress)'
         );
        $mail = $drive_service->about->get($optParams);
        $emailPersonal = $mail->toSimpleObject()->user['emailAddress'];
	//sacar funcion md5
	//$autor=get_autor_md5(md5($id_autor));
	$autor=get_autor_md5($id_autor);
	$mismoUsuario=true;
	if ($autor["CORREO_GMAIL"] != $emailPersonal){
		$mismoUsuario=false;
	}
	load_template_twig("finalizarVista.html", array('autor' => $autor ,'mismoUsuario' => $mismoUsuario));   
}
?>