<?php

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

use App\AgoraScript\Controller\Controller;

// instantiate the loader
$loader = new App\AgoraScript\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\AgoraScript', __DIR__ . '/../src');
// register the autoloader
$loader->register();



// On recupère l'action passée dans l'URL

// If ternaire
//$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "readAll";
// Null coalescing operator
$action = $_REQUEST['action'] ?? "readAll";
$controller = $_REQUEST['controller'] ?? "question";

$etat = $_REQUEST['etat'] ?? 8;

$controllerName = ucfirst($controller);

$controllerClassName = "App\AgoraScript\Controller\Controller$controllerName";

if (class_exists($controllerClassName)){
    $tabMethode = get_class_methods($controllerClassName);
    if(in_array($action, $tabMethode)) $controllerClassName::$action($etat);
    else {
        $class = new $controllerClassName;
        $class->error(); // Appel de la méthode statique $action de ControllerVoiture
    }

}else{
    //ControllerVoiture::error();
}

?>
