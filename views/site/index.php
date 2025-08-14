<?php
$this->title = 'Portal Berita';
 

// Warna & ikon per kategori

?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    background-color: #f8f9fa;
}

.card-news {
    border: none;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.card-news:hover {
    transform: translateY(-3px);
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.15);
}

.news-title {
    color: #495057;
    font-weight: bold;
    font-size: 1.1rem;
    transition: color 0.2s;
}

.news-title:hover {
    color: #0d6efd;
}

.news-desc {
    color: #6c757d;
    font-size: 0.9rem;
}

.badge-clickable {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.badge-clickable:hover {
    transform: scale(1.05);
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
}
</style>

<div class="container my-4">
    <h1 class="mb-4 text-center text-primary fw-bold">
        🌏 Top News Global
    </h1>

    <!-- Filter Category -->
    <form method="get" action="" class="mb-4">
        <div class="row g-2 justify-content-center">
            <div class="col-md-4 col-sm-6">
                <select name="category" class="form-select shadow-sm">
                    <?php foreach ($categoryStyles as $key => $label): ?>
                    <option value="<?= $label['name'] ?>" <?= $label['name'] == $category ? 'selected' : '' ?>>
                        <?= ucfirst($label['name']) ?: 'ALL CATEGORY' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                    class="form-control shadow-sm" placeholder="keyword ... ">
            </div>
            <div class="col-md-2 col-sm-4">
                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </div>
    </form>

    <!-- News List -->
    <div class="row g-3">

        <?php 
            if(count($data )> 0){
        ?>

        <?php
            foreach ($data as $news): 
                $catKey = strtolower($news['category'] ?? 'general');
                $style = $categoryStyles[$catKey] ?? ['color' => 'secondary', 'icon' => 'fa-tag'];
        ?>
        <div class="col-lg-4 col-md-6">
            <a href="<?= $news['url'] ?>" target="_blank" class="text-decoration-none">
                <div class="card card-news h-100 position-relative">

                    <!-- Tombol di kanan bawah -->
                    <a href="<?= $news['url'] ?>" target="_blank"
                        class="btn btn-sm btn-primary position-absolute bottom-0 end-0 m-2"
                        onclick="event.stopPropagation();">
                        <i class="fas fa-external-link-alt"></i>
                    </a>

                    <div class="card-body">
                        <h5 class="news-title">
                            <i class="fas <?= $style['icon'] ?> me-2 text-<?= $style['color'] ?>"></i>
                            <?= $news['name'] ?>
                            <p class="news-desc"><?= $news['url'] ?></p>
                        </h5>
                        <p class="news-desc"><?= $news['description'] ?></p>

                        <div class="mt-2">
                            <span class="badge bg-<?= $style['color'] ?> badge-clickable">
                                <i class="fas <?= $style['icon'] ?> me-1"></i>
                                category : <?= ucfirst($news['category']) ?>
                            </span>
                            <span class="badge bg-warning text-dark badge-clickable">
                                <i class="fas fa-language me-1"></i>
                                language : <?= strtoupper($news['language']) ?>
                            </span>
                            <span class="badge bg-success text-dark badge-clickable">
                                <i class="fas fa-flag me-1"></i>
                                country : <?= strtoupper($news['country']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <?php endforeach; ?>

        <?php }else {?>

        <div class="col-lg-12">
            <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                <div class="text-center">
                    <font class="text-muted fw-bold">SORRY, DATA NOT FOUND</font>
                </div>
            </div>
        </div>

        <?php } ?>


    </div>


    <!-- Pagination -->
    <nav aria-label="News pagination" class="mt-4">

        <div class="col-lg-12">
            <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                <div class="text-center">
                    <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
                        Show <strong><?= $from ?></strong> - <strong><?= $to ?></strong> of
                        <strong><?= $total ?> News</strong>
                    </span>
                </div>
            </div>
        </div>


        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?keyword=<?=$keyword?>&category=<?= $category ?>&page=<?= $page - 1 ?>">«
                    Prev</a>
            </li>
            <?php endif; ?>

            <?php
            if ($page > 3) {
                echo '<li class="page-item"><a class="page-link" href="?&keyword='.$keyword.'&category='.urlencode($category).'&page=1">1</a></li>';
                if ($page > 4) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            $start = max(1, $page - 1);
            $end = min($totalPages, $page + 1);

            for ($i = $start; $i <= $end; $i++) {
                $active = $i == $page ? 'active' : '';
                echo '<li class="page-item '.$active.'">
                        <a class="page-link" href="?keyword='.$keyword.'&category='.urlencode($category).'&page='.$i.'">'.$i.'</a>
                      </li>';
            }

            if ($page < $totalPages - 2) {
                if ($page < $totalPages - 3) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?keyword='.$keyword.'&category='.urlencode($category).'&page='.$totalPages.'">'.$totalPages.'</a>
            </li>';
            }
            ?>

            <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?keyword=<?=$keyword?>&category=<?= $category ?>&page=<?= $page + 1 ?>">Next
                    »</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>