<?php
include('../fragments/database_connect.php');

if (empty($_SESSION['user_id']) || empty($_POST['book_id']) || !isset($_POST['current_page'])) {
    http_response_code(400);
    exit("Missing data");
}

$userId = $_SESSION['user_id'];
$bookId = $_POST['book_id'];
$page = (int)$_POST['current_page'];

$stmt = $pdo->prepare("UPDATE user_library SET current_page = :page WHERE user_id = :uid AND book_id = :bid");
$stmt->execute([':page' => $page, ':uid' => $userId, ':bid' => $bookId]);
?>