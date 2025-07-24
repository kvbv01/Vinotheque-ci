<?php
session_start();
require_once 'config/database.php';

// Autoloader simple pour les contrôleurs et modèles
spl_autoload_register(function ($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    } elseif (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    }
});

// Routeur simple
$request = $_SERVER['REQUEST_URI'];
$base_path = '/vinotheque-ci'; // Ajustez selon votre configuration

// Supprimer le chemin de base et les paramètres de requête
$request = str_replace($base_path, '', $request);
$request = strtok($request, '?');

// Routes
switch ($request) {
    case '/':
    case '':
        $controller = new ProductController();
        $controller->index();
        break;
        
    case '/products':
        $controller = new ProductController();
        $controller->index();
        break;
        
    case preg_match('/^\/product\/(\d+)$/', $request, $matches) ? $request : !$request:
        $controller = new ProductController();
        $controller->show($matches[1]);
        break;
        
    case preg_match('/^\/category\/([a-z0-9-]+)$/', $request, $matches) ? $request : !$request:
        $controller = new ProductController();
        $controller->category($matches[1]);
        break;
        
    case '/search':
        $controller = new ProductController();
        $controller->search();
        break;
        
    case '/cart':
        $controller = new CartController();
        $controller->index();
        break;
        
    case '/cart/add':
        $controller = new CartController();
        $controller->add();
        break;
        
    case '/cart/remove':
        $controller = new CartController();
        $controller->remove();
        break;
        
    case '/checkout':
        $controller = new CartController();
        $controller->checkout();
        break;
        
    case '/login':
        $controller = new AuthController();
        $controller->login();
        break;
        
    case '/register':
        $controller = new AuthController();
        $controller->register();
        break;
        
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case '/admin/products':
        $controller = new ProductController();
        $controller->adminIndex();
        break;
        
    case '/admin/products/create':
        $controller = new ProductController();
        $controller->adminCreate();
        break;
        
    case '/admin/products/store':
        $controller = new ProductController();
        $controller->adminStore();
        break;
        
    default:
        http_response_code(404);
        require 'views/errors/404.php';
        break;
}
?>
