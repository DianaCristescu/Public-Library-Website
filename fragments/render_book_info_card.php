<?php 
    if (!empty($_GET['book'])) {
        $stmt = $pdo->prepare('CALL GetBookInfoFromId(:target_id)');
        $stmt->execute(['target_id' => $_GET['book']]);
        $selectedBook = $stmt->fetch();
        $stmt->closeCursor();

        $closeBook = $_GET;
        unset($closeBook['book']);
        $closeBook = '?' . http_build_query($closeBook);

        if (!empty($_SESSION['user_id'])) {
            $stmt = $pdo->prepare('CALL GetUserWishlistFromId(:target_id)');
            $stmt->execute(['target_id' => $_SESSION['user_id']]);
            $wishlist = $stmt->fetchAll();
            $wishlist = array_column($wishlist, 'id');
            $stmt->closeCursor();

            $stmt = $pdo->prepare('CALL GetBorrowedBooksFromId(:target_id)');
            $stmt->execute(['target_id' => $_SESSION['user_id']]);
            $borrowedBooks = $stmt->fetchAll();
            $borrowedBooks = array_column($borrowedBooks, 'id');
            $stmt->closeCursor();
        }
    }
?>

<?php function renderBookInfoCard() {
    global $pdo;

    if (!empty($_GET['book'])) {
        $stmt = $pdo->prepare('CALL GetBookInfoFromId(:target_id)');
        $stmt->execute(['target_id' => $_GET['book']]);
        $selectedBook = $stmt->fetch();
        $stmt->closeCursor();

        $closeBook = $_GET;
        unset($closeBook['book']);
        $closeBook = '?' . http_build_query($closeBook);

        if (!empty($_SESSION['user_id'])) {
            $stmt = $pdo->prepare('CALL GetUserWishlistFromId(:target_id)');
            $stmt->execute(['target_id' => $_SESSION['user_id']]);
            $wishlist = $stmt->fetchAll();
            $wishlist = array_column($wishlist, 'id');
            $stmt->closeCursor();

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
        }
    }
    if (!empty($selectedBook)): 
        $bookTitle = htmlspecialchars($selectedBook['title']);
        $bookAuthors = htmlspecialchars($selectedBook['author_names']);
        $bookImgUrl = htmlspecialchars($selectedBook['image_path']);
        $bookDescription = htmlspecialchars($selectedBook['description']);
        $bookPageCount = htmlspecialchars($selectedBook['page_count']);
        $bookPublisherName = htmlspecialchars($selectedBook['publisher_name']);
        $bookPublishDate = new DateTime(htmlspecialchars($selectedBook['publish_date']));
        $bookStockCount = htmlspecialchars($selectedBook['stock_count']);
        $bookReservedCount = htmlspecialchars($selectedBook['reserved_count']);
        // $bookLanguageId = htmlspecialchars($selectedBook['language_id']);
        // $bookGoodreadsLink = htmlspecialchars($selectedBook['goodreads_link']);
    ?>
        <section class="book-info">
            <div class="book-top-buttons">
                <button class="square-button" onclick="saveScrollPosition(); location.href='<?= $closeBook ?>';">
                    <i class="fa-solid fa-x"></i>
                </button>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <button class="wishlist-button" onclick="toggleWishlist(<?= $_GET['book'] ?>, this)">
                        <i class="<?= (in_array($_GET['book'], $wishlist) ? 'fa-solid' : 'fa-regular') ?> fa-star"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="scrollable-container">
                <img src="<?= $bookImgUrl ?>" alt="Cover of <?= $bookTitle ?>">
                <div class="book-title">
                    <h3 class="book-title"><?= $bookTitle ?></h3>
                    <p class="book-title-secondary"><?= $bookAuthors ?></p>
                </div>
                <div class="book-data">
                    <p class="book-title-secondary"><?= $bookPublishDate->format('Y') ?><br><?= $bookPublisherName ?></p>
                    <p class="book-title-secondary"><?= $bookPageCount ?><br>Pages</p>
                    <p class="book-title-secondary"><?= $bookStockCount ?><br>In Stock</p>
                    <p class="book-title-secondary"><?= $bookReservedCount ?><br>Reserved</p>
                </div>
                <div class="book-description">
                    <p><?= $bookDescription ?></p>
                </div>
            </div>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <div class="book-bottom-buttons">
                    <button class="green-button" onclick="toggleBorrow(<?= $_GET['book'] ?>, this)"><?= (in_array($_GET['book'], $borrowedBooks) ? 'Return' : 'Borrow') ?></button>
                    <button class="green-button" onclick="toggleReservation(<?= $_GET['book'] ?>, this)"><?= (in_array($_GET['book'], $reservedBooks) ? 'Cancel Reservation' : 'Reserve') ?></button>
                </div>
            <?php endif; ?>
            <script src="/biblioteca/javascript/toggle_wishlist.js"></script>
            <script src="/biblioteca/javascript/toggle_borrow_reservation.js"></script>
        </section>
    <?php endif;
} ?>

