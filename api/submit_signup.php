<?php
    include('../fragments/database_connect.php');

    if (empty($_POST["email"]) || empty($_POST['password']) || empty($_POST["first_name"]) || empty($_POST["last_name"])) {
        die("Email, names and password are required.");
    }

    if ($_POST['password'] != $_POST['confirm_password']) {
        die("Passwords don't match.");
    }

    $stmt = $pdo->prepare("SELECT GetUserIdFromEmail(:email) AS id");
    $stmt->execute([':email' => $_POST["email"]]);
    $id = $stmt->fetch()['id'];
    $stmt->closeCursor();

    if (empty($id)) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, first_name, last_name, password_hash)
                VALUES (:email, :first_name, :last_name, :password_hash)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email' => $_POST["email"],
                'first_name'=> $_POST["first_name"],
                'last_name' => $_POST["last_name"],
                'password_hash' => $hashed_password
            ]);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo "Insert Failed: " . $e->getMessage();
            exit();
        }
    } else {
        die("Email already in use.");
    }
    header("Location: /biblioteca/browse");
?>