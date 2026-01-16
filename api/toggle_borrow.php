<?php
    include('../fragments/database_connect.php');

    if (empty($_SESSION['user_id']) || empty($_POST['book_id'])) {
        die("Error: Missing login or book ID.");
    }

    $userId = $_SESSION['user_id'];
    $bookId = $_POST['book_id'];

    $stmt = $pdo->prepare('CALL GetBorrowedBooksFromId(:target_id)');
    $stmt->execute(['target_id' => $userId]);
    $borrowedBooks = $stmt->fetchAll();
    $stmt->closeCursor();

    $borrowedIds = array_column($borrowedBooks, 'id');
    $isBorrowed = in_array($bookId, $borrowedIds) ? 1 : 0;

    if (!empty($isBorrowed)) {
        $stmt = $pdo->prepare("DELETE FROM loans WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);

        echo ($stmt->rowCount() > 0) ? "0" : "1";
    } else {
        $stmt = $pdo->prepare('SELECT BookInUserLibrary(:target_user_id, :target_book_id) AS bookInLibrary');
        $stmt->execute(['target_user_id' => $userId, 'target_book_id' => $bookId]);
        $bookInLibrary = $stmt->fetchColumn();
        $stmt->closeCursor();

        if (empty($bookInLibrary)) {
            $stmt = $pdo->prepare("INSERT INTO user_library (user_id, book_id) VALUES (:uid, :bid)");
            $success = $stmt->execute([':uid' => $userId, ':bid' => $bookId]);
            $stmt->closeCursor();
            if (!$success) {
                echo "0";
                exit;
            }
        }

        $dueDate = date('Y-m-d', strtotime('+7 days'));
        $stmt = $pdo->prepare("INSERT INTO loans (user_id, book_id, due_date) VALUES (:uid, :bid, :due)");
        $success = $stmt->execute([':uid' => $userId, ':bid' => $bookId, ':due' => $dueDate]);

        echo ($success) ? "1" : "0";
    }
    $stmt->closeCursor();
?>