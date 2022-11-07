<?php

use Controllers\User\UserController;

function autoload($class)
{
    require_once "$class.php";
}
spl_autoload_register("autoload");

$userController = new UserController();
$currentUser = $userController->isLoggedIn() ?? "";

$page = $_GET['page'] ?? '';
$id = $_GET['id'] ?? '';
$showCookie = false;

isset($_COOKIE['accept_cookie']) ? $showCookie = true : $showCookie = false;

ob_start();
if (empty($page)) {

    header('Location: login');
} else {
    if ($page === 'profile') {
        $css = "<link rel='stylesheet' href='assets/css/profile.css'>";
        $userController->profile();
    } elseif ($page === 'register' || $page === 'login') {
        $userController->authUser();
    } elseif ($page === 'accept-cookie') {
        $userController->acceptCookie();
    } elseif ($page === 'logout') {
        $userController->logout();
    }
}
$content = ob_get_clean();
require_once './Views/Common/template.php';
