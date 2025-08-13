<?php
use yii\helpers\Html;

$totalPages = ceil($totalResults / $pageSize);
?>

<div class="list-group">
    <?php foreach ($articles as $article): ?>
    <div class="list-group-item list-group-item-action py-3 border-0 border-bottom"
        style="cursor: pointer; min-height: 180px;"
        onclick="window.open('<?= Html::encode($article['url']) ?>', '_blank')">
        <div class="row g-3 align-items-start">
            <!-- Gambar -->
            <div class="col-4 col-md-3">
                <img src="<?= Html::encode($article['urlToImage'] ?? 'https://via.placeholder.com/150') ?>"
                    alt="News Image" class="img-fluid rounded" style="width: 100%; height: 150px; object-fit: cover;">
            </div>
            <!-- Konten -->
            <div class="col-8 col-md-9 d-flex flex-column">
                <div>
                    <h5 class="mb-1 fw-semibold"><?= Html::encode($article['title']) ?></h5>
                    <?php if(!empty($article['source']['name'])): ?>
                    <small class="text-muted d-block mb-1">
                        <?= Html::encode($article['source']['name']) ?> •
                        <?= date('d M Y', strtotime($article['publishedAt'])) ?>
                    </small>
                    <?php endif; ?>
                    <?php if(!empty($article['description'])): ?>
                    <p class="mb-2 text-secondary small"><?= Html::encode($article['description']) ?></p>
                    <?php endif; ?>
                </div>
                <br>
                <div style="margin-top: 15px;" class="mt-auto">
                    <button class="btn btn-primary btn-sm"
                        onclick="event.stopPropagation(); window.open('<?= Html::encode($article['url']) ?>', '_blank')">
                        <i class="bi bi-box-arrow-up-right"></i> Open
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if($totalPages > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php if($currentPage > 1): ?>
        <li class="page-item"><a class="page-link" href="#" data-page="<?= $currentPage-1 ?>">&laquo; Prev</a></li>
        <?php endif; ?>

        <?php
        $range = 2;
        $start = max(1, $currentPage - $range);
        $end = min($totalPages, $currentPage + $range);

        if($start > 1){
            echo '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
            if($start > 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        for($i=$start;$i<=$end;$i++){
            if($i==$currentPage){
                echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
            }else{
                echo '<li class="page-item"><a class="page-link" href="#" data-page="'.$i.'">'.$i.'</a></li>';
            }
        }

        if($end<$totalPages){
            if($end<$totalPages-1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            echo '<li class="page-item"><a class="page-link" href="#" data-page="'.$totalPages.'">'.$totalPages.'</a></li>';
        }

        if($currentPage < $totalPages){
            echo '<li class="page-item"><a class="page-link" href="#" data-page="'.($currentPage+1).'">Next &raquo;</a></li>';
        }
        ?>
    </ul>
</nav>
<?php endif; ?>