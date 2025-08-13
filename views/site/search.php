<?php
$this->title = 'Hasil Pencarian - Portal Berita Yii2';

// Warna & ikon per kategori
$categoryStyles = [
    'business'      => ['color' => 'primary', 'icon' => 'fa-briefcase'],
    'entertainment' => ['color' => 'warning', 'icon' => 'fa-film'],
    'general'       => ['color' => 'secondary', 'icon' => 'fa-globe'],
    'health'        => ['color' => 'success', 'icon' => 'fa-heartbeat'],
    'science'       => ['color' => 'info', 'icon' => 'fa-flask'],
    'sports'        => ['color' => 'danger', 'icon' => 'fa-football-ball'],
    'technology'    => ['color' => 'dark', 'icon' => 'fa-microchip'],
];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container my-4">
    <h1 class="mb-4 text-center text-primary fw-bold">
        🔍 Hasil Pencarian untuk: <span class="text-dark"><?= htmlspecialchars($keyword) ?></span>
    </h1>

    <!-- Form Pencarian -->
    <form method="get" action="<?= \yii\helpers\Url::to(['site/search']) ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari berita..."
                value="<?= Yii::$app->request->get('q') ?>">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>

    <?php if (empty($articles)): ?>
    <div class="alert alert-warning text-center">
        Tidak ada berita ditemukan untuk "<strong><?= htmlspecialchars($keyword) ?></strong>".
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($articles as $news): 
                $catKey = strtolower($news['source']['name'] ?? 'general'); // Bisa juga pakai kategori jika ada
                $style = $categoryStyles[$catKey] ?? ['color' => 'secondary', 'icon' => 'fa-tag'];
            ?>
        <div class="col-lg-4 col-md-6">
            <a href="<?= $news['url'] ?>" target="_blank" class="text-decoration-none">
                <div class="card card-news h-100">
                    <?php if (!empty($news['urlToImage'])): ?>
                    <img src="<?= $news['urlToImage'] ?>" class="card-img-top" alt="News Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="news-title">
                            <i class="fas <?= $style['icon'] ?> me-2 text-<?= $style['color'] ?>"></i>
                            <?= $news['title'] ?>
                        </h5>
                        <p class="news-desc"><?= $news['description'] ?></p>
                        <div class="mt-2">
                            <span class="badge bg-<?= $style['color'] ?> badge-clickable">
                                <i class="fas <?= $style['icon'] ?> me-1"></i>
                                <?= htmlspecialchars($news['source']['name'] ?? 'Unknown') ?>
                            </span>
                            <?php if (!empty($news['author'])): ?>
                            <span class="badge bg-warning text-dark badge-clickable">
                                <i class="fas fa-user me-1"></i>
                                <?= htmlspecialchars($news['author']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>