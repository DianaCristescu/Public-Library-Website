<?php include('fragments/check_login.php')?>
<?php
    // Clear params
    $_SESSION = [];
    session_destroy();

    // Clear cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Redirect
    header("Location: browse");
?>