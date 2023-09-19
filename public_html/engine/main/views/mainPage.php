<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/header.php';
?>


<section>
    <div class="container">
        <div class="section-title">
            Мои видео
        </div>
        <div class="toVidList">
            <a href="/studio">Загрузить видео <ion-icon name="arrow-forward-outline"></ion-icon></a>
        </div>
        <div class="videos-list">

            <?php for($i=0; $i < 10; $i++): ?>
                <div class="video">
                    <video width="250" height="150" controls>
                        <source src="<?=SITE_URL?>files/uploads/skyrimTest.mp4" type="video/mp4">
                    </video>
                </div>
            <?php endfor; ?>


        </div>
</section>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/footer.php';
?>
