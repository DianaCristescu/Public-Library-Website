<link rel="stylesheet" href="./style/book_card.css">

<!-- Includes all data -->
<?php function bookCardStyle1($bookTitle, $bookAuthors, $bookImgUrl, $bookId, $pageCount, $currentPage, $isBorrowed, $isReserved) { 
    $percent = ($pageCount > 0) ? min(100, round(($currentPage / $pageCount) * 100)) : 0; ?>
    <article class="book-progress-card">
        <img src="<?= $bookImgUrl ?>" alt="Cover of <?= $bookTitle ?>">
        <div class="book-progress-title">
            <h3><a href="?<?= http_build_query(array_merge($_GET, ['book' => $bookId])) ?>" onclick="saveScrollPosition()"><?= $bookTitle ?></a></h3>
            <p class="author"><?= $bookAuthors ?></p>
        </div>
        <div class="book-progress-status">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-<?= $bookId ?>" style="width: <?= $percent ?>%;"></div> 
            </div>
            <div class="progress-input-container">
                <input type="number" 
                       class="page-input" 
                       value="<?= $currentPage ?>" 
                       min="0" 
                       max="<?= $pageCount ?>"
                       data-book-id="<?= $bookId ?>"
                       data-total-pages="<?= $pageCount ?>"
                       onkeydown="updatePageProgress(event, this)"
                >
                <span class="progress-text-static"> / <?= $pageCount ?> Pages</span>
            </div>
        </div>
        <div class="book-progress-buttons">
            <button class="green-button" onclick="toggleBorrow(<?= $bookId ?>, this)"><?= ($isBorrowed ? 'Return' : 'Borrow') ?></button>
            <button class="green-button" onclick="toggleReservation(<?= $bookId ?>, this)"><?= ($isReserved ? 'Cancel Reservation' : 'Reserve') ?></button>
        </div>
    </article>
<?php } ?>

<!-- No reserve -->
<?php function bookCardStyle2($bookTitle, $bookAuthors, $bookImgUrl, $bookId, $pageCount, $currentPage, $isBorrowed) {
    $percent = ($pageCount > 0) ? min(100, round(($currentPage / $pageCount) * 100)) : 0; ?>
    <article class="book-progress-card">
        <img src="<?= $bookImgUrl ?>" alt="Cover of <?= $bookTitle ?>">
        <div class="book-progress-title">
            <h3><a href="?<?= http_build_query(array_merge($_GET, ['book' => $bookId])) ?>" onclick="saveScrollPosition()"><?= $bookTitle ?></a></h3>
            <p class="author"><?= $bookAuthors ?></p>
        </div>
        <div class="book-progress-status">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-<?= $bookId ?>" style="width: <?= $percent ?>%;"></div> 
            </div>
            <div class="progress-input-container">
                <input type="number" 
                       class="page-input" 
                       value="<?= $currentPage ?>" 
                       min="0" 
                       max="<?= $pageCount ?>"
                       data-book-id="<?= $bookId ?>"
                       data-total-pages="<?= $pageCount ?>"
                       onkeydown="updatePageProgress(event, this)"
                >
                <span class="progress-text-static"> / <?= $pageCount ?> Pages</span>
            </div>
        </div>    
        <div class="book-progress-buttons">
            <button class="green-button" onclick="toggleBorrow(<?= $bookId ?>, this)"><?= ($isBorrowed ? 'Return' : 'Borrow') ?></button>
        </div>    
    </article>
<?php } ?>

<!-- No book progress -->
<?php function bookCardStyle3($bookTitle, $bookAuthors, $bookImgUrl, $bookId, $isBorrowed, $isReserved) { ?>
    <article class="book-progress-card">
        <img src="<?= $bookImgUrl ?>" alt="Cover of <?= $bookTitle ?>">
        <div class="book-progress-title">
            <h3><a href="?<?= http_build_query(array_merge($_GET, ['book' => $bookId])) ?>" onclick="saveScrollPosition()"><?= $bookTitle ?></a></h3>
            <p class="author"><?= $bookAuthors ?></p>
        </div>
        <div class="book-progress-buttons">
            <button class="green-button" onclick="toggleBorrow(<?= $bookId ?>, this)"><?= ($isBorrowed ? 'Return' : 'Borrow') ?></button>
            <button class="green-button" onclick="toggleReservation(<?= $bookId ?>, this)"><?= ($isReserved ? 'Cancel Reservation' : 'Reserve') ?></button>
        </div>                
    </article>
<?php } ?>

<?php function renderBookCards($listOfBooks, $cardStyle) {
    global $pdo;

    $stmt = $pdo->prepare('CALL GetBorrowedBooksFromId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $borrowedBooks = $stmt->fetchAll();
    $borrowedBooks = array_column($borrowedBooks, 'id');
    $stmt->closeCursor();

    $stmt = $pdo->prepare('CALL GetReservedBooksFromId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $reservedBooks = $stmt->fetchAll();
    $reservedBooks = array_column($reservedBooks, 'id');
    $stmt->closeCursor();

    foreach ($listOfBooks as $book): 
        $bookTitle = htmlspecialchars($book['title']);
        $bookAuthors = htmlspecialchars($book['author_names']);
        $bookImgUrl = htmlspecialchars($book['image_path']);
        $bookId = htmlspecialchars($book['id']);

        if ($cardStyle == 1 or $cardStyle == 2) {
            $pageCount = htmlspecialchars($book['page_count']);
            $currentPage = htmlspecialchars($book['current_page']);

            if ($cardStyle == 1) {
                bookCardStyle1($bookTitle, $bookAuthors, $bookImgUrl, $bookId, $pageCount, $currentPage, in_array($bookId, $borrowedBooks), in_array($bookId, $reservedBooks));
            } elseif ($cardStyle == 2) {
                bookCardStyle2($bookTitle, $bookAuthors, $bookImgUrl, $bookId, $pageCount, $currentPage, in_array($bookId, $borrowedBooks));
            }
        } elseif ($cardStyle == 3) {
            bookCardStyle3($bookTitle, $bookAuthors, $bookImgUrl, $bookId, in_array($bookId, $borrowedBooks), in_array($bookId, $reservedBooks));
        } ?>

    <script src="/biblioteca/javascript/toggle_borrow_reservation.js"></script>
    <script src="/biblioteca/javascript/update_progress.js"></script>
    <?php endforeach;
} ?>