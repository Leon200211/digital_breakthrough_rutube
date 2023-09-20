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

            <?php foreach ($this->videoData as $key => $video): ?>
                <div class="video">
                    <video width="250" height="150" controls>
                        <source src="<?=SITE_URL?>files/uploads/<?=$video['video']?>" type="video/mp4">
                    </video>
                    <div>
                        <?=$video['name']?>
                    </div>
                    <div>
                        <?=$video['description']?>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div>
                            <?=$video['date_create']?>
                        </div>
                        <div>
                            <?php if($video['quality']): ?>
                                <img src="<?=SITE_URL?>templates/default/free-icon-high-quality-7479467.png" height="20px;" width="20px;">
                            <?php endif; ?>
                            <?php if($video['commentary']): ?>
                                <img src="<?=SITE_URL?>templates/default/free-icon-sound-3293603.png" height="20px;" width="20px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


        </div>
</section>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/footer.php';
?>
