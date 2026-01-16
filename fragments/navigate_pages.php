<div class="pages">
    <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => ($pageNum > 1) ? $pageNum - 1 : 1])) ?>'"><i class="fa-solid fa-chevron-left"></i></button>
    <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>'">1</button>
    <?php if ($maxPageNum > 1): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => 2])) ?>'">2</button>
    <?php endif; ?>
    <?php if ($maxPageNum > 2): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => 3])) ?>'">3</button>
    <?php endif; ?>
    <?= ($pageNum > 5) ? '. . .' : '' ?>
    <?php if ($pageNum > 4 and $pageNum < $maxPageNum - 1): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $pageNum - 1])) ?>'"><?= $pageNum - 1 ?></button>
    <?php endif; ?>
    <?php if ($pageNum > 3 and $pageNum < $maxPageNum - 2): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $pageNum])) ?>'"><?= $pageNum ?></button>
    <?php endif; ?>
    <?php if ($pageNum > 2 and $pageNum < $maxPageNum - 3): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $pageNum + 1])) ?>'"><?= $pageNum + 1 ?></button>
    <?php endif; ?>
    <?= ($pageNum < $maxPageNum - 4) ? '. . .' : '' ?>
    <?php if ($maxPageNum > 5): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $maxPageNum - 2])) ?>'"><?= $maxPageNum - 2 ?></button>
    <?php endif; ?>
    <?php if ($maxPageNum > 4): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $maxPageNum - 1])) ?>'"><?= $maxPageNum - 1 ?></button>
    <?php endif; ?>
    <?php if ($maxPageNum > 3): ?>
        <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $maxPageNum])) ?>'"><?= $maxPageNum ?></button>
    <?php endif; ?>
    <button class="square-button" onClick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => ($pageNum < $maxPageNum) ? $pageNum + 1 : $maxPageNum])) ?>'"><i class="fa-solid fa-chevron-right"></i></button>
</div>