<?php 
    require_once __DIR__ . '/database_connect.php';
    
    if (isset($_SESSION['user_id'])) {
		$stmt = $pdo->prepare('CALL GetUserInfoFromId(:target_id)');
		$stmt->execute(['target_id' => $_SESSION['user_id']]);
		$userInfo = $stmt->fetch();
		$stmt->closeCursor();
    }
?>

<!DOCTYPE html>
<html lang="eng">

    <head>
        <title>Biblioteca</title>

        <!-- Metdata -->
        <meta charset="UTF-8" />
        <meta name="author" content="Diana Cristescu">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords"
            content=""><!-- cuvintele cheie ale paginii, optimizare pentru aparitia in search engine -->
        <meta name="description" content=""><!-- descrierea paginii, adesea apare sub link in search engine -->

        <!-- Favicon -->
        <!-- <link rel="icon" type="image/png" href="/images/ico/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/images/ico/favicon.svg" />
        <link rel="shortcut icon" href="/images/ico/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/images/ico/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Oakniq" />
        <link rel="manifest" href="/images/ico/site.webmanifest" /> -->

        <!-- Style -->
        <link rel="stylesheet" href="/biblioteca/style/style.css">
        <link rel="stylesheet" href="/biblioteca/style/menu.css">
        <script src="https://kit.fontawesome.com/b6acd513ff.js" crossorigin="anonymous"></script>

        <style>
            :root {
                /* Color Theme Swatches */
                --dark-gray: #8C8C8C;
                --gray: #bdbfc0;
                --light-gray: #e0e0e0;
                --very-light-gray: #f4f4f4;
                --accent-color-main: #539165;
                --accent-color-secondary: #F7C04A;
                --accent-color-tertiary: #9B59B6;
            }
        </style>
