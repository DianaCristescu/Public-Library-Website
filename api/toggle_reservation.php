<?php
    include('../fragments/database_connect.php');

    if (empty($_SESSION['user_id']) || empty($_POST['book_id'])) {
        die("Error: Missing login or book ID.");
    }

    $userId = $_SESSION['user_id'];
    $bookId = $_POST['book_id'];

    $stmt = $pdo->prepare('CALL GetReservedBooksFromId(:target_id)');
    $stmt->execute(['target_id' => $userId]);
    $reservedBooks = $stmt->fetchAll();
    $stmt->closeCursor();

    $reservedIds = array_column($reservedBooks, 'id');
    $isReserved = in_array($bookId, $reservedIds) ? 1 : 0;

    if (!empty($isReserved)) {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);

        echo ($stmt->rowCount() > 0) ? "0" : "1";
    } else {
        $endDate = date('Y-m-d', strtotime('+0 days'));
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, book_id, end_date) VALUES (:uid, :bid, :end_date)");
        $success = $stmt->execute([':uid' => $userId, ':bid' => $bookId, ':end_date' => $endDate]);

        echo ($success) ? "1" : "0";
    }
    $stmt->closeCursor();
?>