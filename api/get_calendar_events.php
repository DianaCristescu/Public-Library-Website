<?php
include('../fragments/database_connect.php');

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$response = ['loans' => [], 'reservations' => []];

try {
    // Get Borrowed Books
    $stmt = $pdo->prepare('CALL GetBorrowedBooksFromId(:uid)');
    $stmt->execute(['uid' => $userId]);
    $response['loans'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    // Get Reserved Books
    $stmt = $pdo->prepare('CALL GetReservedBooksFromId(:uid)');
    $stmt->execute(['uid' => $userId]);
    $response['reservations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>