<?php

class AuthHelper
{

    function require_login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /ecommerce/client/login");
            exit;
        }
    }

}

?>

