<?php
    include('../fragments/database_connect.php');

    if (empty($_SESSION['user_id']) || empty($_POST['book_id'])) {
        die("Error: Missing login or book ID.");
    }

    $userId = $_SESSION['user_id'];
    $bookId = $_POST['book_id'];

    $stmt = $pdo->prepare('CALL GetUserWishlistFromId(:target_id)');
    $stmt->execute(['target_id' => $userId]);
    $wishlist = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $stmt->closeCursor();

    $inWishlist = in_array($bookId, $wishlist) ? 1 : 0;

    if (!empty($inWishlist)) {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);

        echo ($stmt->rowCount() > 0) ? "0" : "1";
    } else {
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, book_id) VALUES (:uid, :bid)");
        $success = $stmt->execute([':uid' => $userId, ':bid' => $bookId]);

        echo ($success) ? "1" : "0";
    }
    $stmt->closeCursor();
?>