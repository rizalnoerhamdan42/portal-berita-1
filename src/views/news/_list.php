<?php
use yii\helpers\Html;

$totalPages = ceil($totalResults / $pageSize);
?>

<div>
    <p>Total results: <?= $totalResults ?></p>
    <ul>
        <?php foreach ($articles as $article): ?>
        <li>
            <a href="<?= Html::encode($article['url']) ?>" target="_blank">
                <?= Html::encode($article['title']) ?>
            </a><br />
            <small><?= Html::encode($article['source']['name']) ?> -
                <?= Html::encode($article['publishedAt']) ?></small>
            <p><?= Html::encode($article['description']) ?></p>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $page): ?>
    <span><strong><?= $i ?></strong></span>
    <?php else: ?>
    <a href="#" data-page="<?= $i ?>"><?= $i ?></a>
    <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>