<?php
namespace Admin\Controllers;

class DashboardController
{
    public function index()
    {
        echo 5;
        $pageTitle = "Panel de Administración";
        require_once ADMIN_APP_PATH . '/views/dashboard/index.php';
    }
}
