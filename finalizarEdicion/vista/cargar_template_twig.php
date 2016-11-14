<?php

function load_template_twig($template, $array){

  require_once 'vendor/autoload.php';

  Twig_Autoloader::register();

  $templateDir = "vista/";

  $loader = new Twig_Loader_Filesystem($templateDir);

  $twig = new Twig_Environment($loader);
              
  $template = $twig->loadTemplate($template);

  $template->display($array);

}

?>