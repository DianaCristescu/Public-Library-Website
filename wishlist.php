<?php include('fragments/check_login.php')?>
<?php include('fragments/head.php')?>
<?php include('fragments/menu.php') ?>
<?php include('fragments/render_book_card_list.php') ?>
<?php include('fragments/render_book_info_card.php') ?>
<?php

    $stmt = $pdo->prepare('CALL GetUserWishlistFromId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $wishlist = $stmt->fetchAll();
    $stmt->closeCursor();

?>

<div class="page-layout">
    <div class="page-title">
        <h1 class="title1">Wishlist</h1>
    </div>
    <div class="page-searchbox">
        <input type="text" name="searchbox" placeholder="Search for title, author, etc...">
        <img src="./resources/icons/search_static.png"
            onmouseover="this.src='./resources/icons/search_anim.apng'"
            onmouseout="this.src='./resources/icons/search_static.png'">
    </div>
    <div class="page-content">
        <?php renderBookCards($wishlist, 3) ?>
        <?php renderBookInfoCard() ?>
    </div>
</div>

<?php include('fragments/foot.php') ?>