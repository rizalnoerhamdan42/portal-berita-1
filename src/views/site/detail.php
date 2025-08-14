<?php
use yii\helpers\Html;

$this->title = $title;
?>

<div class="container mt-4">
    <h1><?= Html::encode($title) ?></h1>
    <p class="text-muted">
        <?= Html::encode($source) ?> | <?= date('d M Y H:i', strtotime($publishedAt)) ?>
        <?php if ($author): ?> | By <?= Html::encode($author) ?><?php endif; ?>
    </p>
    <?php if ($image): ?>
    <img src="<?= Html::encode($image) ?>" alt="Image" class="img-fluid mb-3">
    <?php endif; ?>
    <p><strong><?= Html::encode($description) ?></strong></p>
    <p><?= nl2br(Html::encode($content)) ?></p>
    <a href="<?= Html::encode($url) ?>" target="_blank" class="btn btn-primary">Read Original Article</a>
</div>