<?php include('fragments/check_login.php')?>
<?php include('fragments/head.php')?>
<?php include('fragments/menu.php') ?>
<?php include('fragments/render_book_card_list.php') ?>
<?php include('fragments/render_book_info_card.php') ?>
<?php

    $stmt = $pdo->prepare('CALL GetCompletedBooksForUserId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $finishedBooks = $stmt->fetchAll();
    $stmt->closeCursor();

    $stmt = $pdo->prepare('CALL GetInProgressBooksForUserId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $inProgressBooks = $stmt->fetchAll();
    $stmt->closeCursor();

    $stmt = $pdo->prepare('CALL GetJustAddedBooksForUserId(:target_id)');
    $stmt->execute(['target_id' => $_SESSION['user_id']]);
    $justAddedBooks = $stmt->fetchAll();
    $stmt->closeCursor();

?>

<div class="page-layout">
    <div class="page-title">
        <h1 class="title1">My Books</h1>
    </div>
    <div class="page-searchbox">
        <input type="text" name="searchbox" placeholder="Search for title, author, etc...">
        <img src="./resources/icons/search_static.png"
            onmouseover="this.src='./resources/icons/search_anim.apng'"
            onmouseout="this.src='./resources/icons/search_static.png'">
    </div>
    <div class="page-content">
        <section id="my-books-just-added">
            <h2 class="title2 sub-title">Just Added</h2>
            <?php renderBookCards($justAddedBooks, 1) ?>
        </section>
        <section id="my-books-in-progress">
            <h2 class="title2 sub-title">In Progress</h2>
            <?php renderBookCards($inProgressBooks, 1) ?>
        </section>
        <section id="my-books-finished">
            <h2 class="title2 sub-title">Finished</h2>
            <?php renderBookCards($finishedBooks, 1) ?>
        </section>
        <?php renderBookInfoCard() ?>
    </div>
</div>

<?php include('fragments/foot.php') ?>


            <!-- <article class="book-progress-card">
                <img src="./resources/images/book_covers/Berserk Volume 1.jpg" alt="Cover of Berserk Volume 1">
                <div class="book-progress-title">
                    <h3>Berserk Volume 1</h3>
                    <p class="author">Kentaro Miura</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/Dune.jpg" alt="Cover of Dune">
                <div class="book-progress-title">
                    <h3>Dune</h3>
                    <p class="author">Frank Herbert</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/One Dark Window.jpg" alt="Cover of One Dark Window">
                <div class="book-progress-title">
                    <h3>One Dark Window</h3>
                    <p class="author">Rachel Gillig</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/Strange Pictures.jpg" alt="Cover of Strange Pictures">
                <div class="book-progress-title">
                    <h3>Strange Pictures</h3>
                    <p class="author">Uketsu</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/The Bell Jar.jpg" alt="Cover of The Bell Jar">
                <div class="book-progress-title">
                    <h3>The Bell Jar</h3>
                    <p class="author">Sylvia Plath</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/The Catcher in the Rye.jpg" alt="Cover of The Catcher in the Rye">
                <div class="book-progress-title">
                    <h3>The Catcher in the Rye</h3>
                    <p class="author">Jerome David Salinger</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/The Devils.jpg" alt="Cover of The Devils">
                <div class="book-progress-title">
                    <h3>The Devils</h3>
                    <p class="author">Joe Abercrombie</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article>
            <article class="book-progress-card">
                <img src="./resources/images/book_covers/The King in Yellow.jpg" alt="Cover of The King in Yellow">
                <div class="book-progress-title">
                    <h3>The King in Yellow</h3>
                    <p class="author">Robert Chambers, Leslie Klinger, Eric Guignard</p>
                </div>
                <div class="book-progress-status">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%;"></div> 
                    </div>
                    <p class="progress-text">122 / 300 Pages</p>
                </div>
                <div class="book-progress-buttons">
                    <button class="green-button">Borrow</button>
                    <button class="green-button">Update</button>
                </div>                
            </article> -->