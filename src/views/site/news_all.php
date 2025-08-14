<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'All News';
$ajaxUrl = Url::to(['site/all']);
 
?>

<div class="container mt-4">
    <h1 class="mb-4 text-primary"><i class="bi bi-newspaper"></i> <?= Html::encode($this->title) ?></h1>

    <!-- Filter Search -->
    <div class="card p-3 mb-4 shadow-sm rounded-3 border-0">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" id="search-keyword" class="form-control" placeholder="default is android "
                    value="<?= Html::encode($keyword) ?>">
            </div>
            <div class="col-md-3">
                <input type="date" id="from-date" class="form-control" value="<?= Html::encode($from) ?>">
            </div>
            <div class="col-md-3">
                <input type="date" id="to-date" class="form-control" value="<?= Html::encode($to) ?>">
            </div>
            <div class="col-md-2">
                <button id="btn-search" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </div>

    <!-- List News -->
    <div id="news-list">
        <?= $this->render('_list', [
            'articles' => $articles,
            'totalResults' => $totalResults,
            'pageSize' => $pageSize,
            'currentPage' => $currentPage
        ]) ?>
    </div>
</div>

<?php
$js = <<<JS
function loadNews(page = $currentPage) {
    $.ajax({
        url: '{$ajaxUrl}',
        data: {
            keyword: $('#search-keyword').val(),
            from: $('#from-date').val(),
            to: $('#to-date').val(),
            page: page
        },
        beforeSend: function() {
            $('#news-list').html('<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(data) {
            $('#news-list').html(data);
        }
    });
}

$('#btn-search').on('click', function() {
    loadNews($currentPage);
});

$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    if(page) loadNews(page);
});
JS;
$this->registerJs($js);
?>