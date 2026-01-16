<?php include('fragments/head.php') ?>
<link rel="stylesheet" href="./style/browse.css">
<?php include('fragments/menu.php') ?>
<?php include('fragments/render_book_info_card.php') ?>

<?php
	$pageNum = $_GET['page'] ?? 1;
	$pageNum = (int)$pageNum;
	if ($pageNum < 1) {
		$pageNum = 1;
	}

	$contentTitle = 'New Arrivals';

	if (!empty($_GET['collection'])) {
		$stmt = $pdo->prepare('SELECT name FROM collections WHERE id = ?');
		$stmt->execute([$_GET['collection']]);
		$contentTitle = $stmt->fetchColumn();
		$stmt->closeCursor();

		$stmt = $pdo->prepare('CALL GetBooksFromCollection(:target_collection_id, :page_num, @max_page_num)');
		$stmt->execute(['target_collection_id' => $_GET['collection'], 'page_num' => $pageNum]);
	} else if (!empty($_GET['category'])) {
		$stmt = $pdo->prepare('SELECT name FROM categories WHERE id = ?');
		$stmt->execute([$_GET['category']]);
		$contentTitle = $stmt->fetchColumn();
		$stmt->closeCursor();

		$stmt = $pdo->prepare('CALL GetBooksFromCategory(:target_category_id, :page_num, @max_page_num)');
		$stmt->execute(['target_category_id' => $_GET['category'], 'page_num' => $pageNum]);
	} else {
		$stmt = $pdo->prepare('CALL GetLatestArrivals(:page_num, @max_page_num)');
		$stmt->execute(['page_num' => $pageNum]);
	}
	$books = $stmt->fetchAll();
	$stmt->closeCursor();
	$maxPageNum = $pdo->query("SELECT @max_page_num AS max_page_num")->fetch()['max_page_num'];

	$stmt = $pdo->prepare('CALL GetCollections()');
	$stmt->execute();
	$collections = $stmt->fetchAll();
	$stmt->closeCursor();
?>

<div class="page-layout">
	<div class="page-title">
		<h1 class="title1">Browse</h1>
	</div>
	<div class="page-searchbox">
		<input type="text" name="searchbox" placeholder="Search for title, author, etc...">
		<img src="./resources/icons/search_static.png"
				 onmouseover="this.src='./resources/icons/search_anim.apng'"
				 onmouseout="this.src='./resources/icons/search_static.png'">
	</div>
	<div class="page-content">
		<section id="browse-grid-collections">
			<h2 class="title2 sub-title">Collections</h2>
			<div class="flex-container-horizontal">
				<?php 
					foreach ($collections as $collection):
						$collectionName = htmlspecialchars($collection['name']);
						$collectionId = htmlspecialchars($collection['id']);
				?>
						<div class="collection-category">
							<h3><a href="?collection=<?= $collectionId ?>"><?= $collectionName ?></a></h3>
							<ul>

								<?php 
									$stmt = $pdo->prepare('CALL GetCategoriesByCollection(:id)');
									$stmt->execute(['id' => $collectionId]);
									$categories = $stmt->fetchAll();
									$stmt->closeCursor();
									
									foreach ($categories as $category):
										$categoryName = htmlspecialchars($category['name']); 
										$categoryId = htmlspecialchars($category['id']);
								?>
								<li><a href="?category=<?= $categoryId ?>"><?= $categoryName ?></a></li>
								<?php endforeach; ?>

							</ul>
						</div>
				<?php endforeach; ?>
			</div>
		</section>
		<section id="browse-grid-new-arrivals">
			<h2 class="title2 sub-title"><?= $contentTitle ?></h2>
			<div class="flex-container-horizontal browse-books">
				<?php 
				foreach ($books as $book):
					$bookTitle = htmlspecialchars($book['title']);
					$bookAuthors = htmlspecialchars($book['author_names']);
					$bookImgUrl = htmlspecialchars($book['image_path']);
					$bookId = htmlspecialchars($book['id']);
				?>
					<article class="book-card">
						<img src="<?= $bookImgUrl ?>" alt="Cover of <?= $bookTitle ?>">
						<h3 class="book-title"><a href="?<?= http_build_query(array_merge($_GET, ['book' => $bookId])) ?>" onclick="saveScrollPosition()"><?= $bookTitle ?></a></h3>
						<p class="author book-title-secondary"><?= $bookAuthors ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<?php include('fragments/navigate_pages.php') ?>
		</section>
		<?php renderBookInfoCard() ?>
	</div>
</div>

<?php include('fragments/foot.php') ?>


				<!-- <article class="book-card">
						<img src="./resources/images/book_covers/Berserk Volume 1.jpg" alt="Cover of Berserk Volume 1">
						<h3>Berserk Volume 1</h3>
						<p class="author">Kentaro Miura</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/Dune.jpg" alt="Cover of Dune">
						<h3>Dune</h3>
						<p class="author">Frank Herbert</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/One Dark Window.jpg" alt="Cover of One Dark Window">
						<h3>One Dark Window</h3>
						<p class="author">Rachel Gillig</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/Strange Pictures.jpg" alt="Cover of Strange Pictures">
						<h3>Strange Pictures</h3>
						<p class="author">Uketsu</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/The Bell Jar.jpg" alt="Cover of The Bell Jar">
						<h3>The Bell Jar</h3>
						<p class="author">Sylvia Plath</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/The Catcher in the Rye.jpg" alt="Cover of The Catcher in the Rye">
						<h3>The Catcher in the Rye</h3>
						<p class="author">Jerome David Salinger</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/The Devils.jpg" alt="Cover of The Devils">
						<h3>The Devils</h3>
						<p class="author">Joe Abercrombie</p>
				</article>
				<article class="book-card">
						<img src="./resources/images/book_covers/The King in Yellow.jpg" alt="Cover of The King in Yellow">
						<h3>The King in Yellow</h3>
						<p class="author">Robert Chambers, Leslie Klinger, Eric Guignard</p>
				</article> -->