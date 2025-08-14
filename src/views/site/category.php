<h2>Kategori: <?= ucfirst($category) ?></h2>
<ul>
    <?php foreach ($articles as $news): ?>
        <li>
            <a href="<?= $news['url'] ?>" target="_blank"><?= $news['title'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
