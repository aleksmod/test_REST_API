<?php


require 'vendor/autoload.php';
define('ROOT_PATH', dirname(__FILE__));

$parts = parse_url($_SERVER['REQUEST_URI']);
$path = trim($parts['path'], '/');

$parts = explode('/', $path);
$parts = array_filter($parts);

// Check if request resource parameters available
if (empty($parts)) {
    header('Bad Request', true, 400);
    exit();
}

$resource = $parts[0];
$resourceId = !empty($parts[1]) ? $parts[1] : null;

// Check if resource controller exists
$controllerClass = ucfirst($resource) . 'Controller';
$controllerClass = "\\Controllers\\{$controllerClass}";
if (!class_exists($controllerClass)) {
    header('Not Found', true, 404);
    exit();
}

$method = strtolower($_SERVER['REQUEST_METHOD']);
if (empty($resourceId)
    && in_array($method, ['put', 'patch', 'delete'])
) {
    header('Bad Request', true, 400);
    exit();
}

header('Content-Type: text/json');
header('Pragma: no-cache;must-revalidate');

$controller = new $controllerClass();
$request = file_get_contents('php://input');
$controller->setRequestBody($request);

if (!method_exists($controller, $method)) {
    header('Not implemented', true, 501);
    exit;
}
$controller->$method($resourceId);
