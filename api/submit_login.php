<?php
    include('../fragments/database_connect.php');

    if (empty($_POST["email"]) || empty($_POST['password'])) {
        die("Email and password are required.");
    }

    $stmt = $pdo->prepare("SELECT GetUserIdFromEmail(:email) AS id");
    $stmt->execute([':email' => $_POST["email"]]);
    $id = $stmt->fetchColumn();
    $stmt->closeCursor();

    $stmt = $pdo->prepare("SELECT GetPasswordHashFromUserId(:user_id) AS password_hash");
    $stmt->execute([':user_id' => $id]);
    $password_hash = $stmt->fetchColumn();
    $stmt->closeCursor();

    if (password_verify($_POST['password'], $password_hash)) {
        $_SESSION['user_id'] = $id;
        header("Location: /biblioteca/browse");
        exit();
    } else {
        echo "Invalid email or password.";
    }
?>