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
            <a href="<?=$SITE_URL?>studio?path=studio">Загрузить видео <ion-icon name="arrow-forward-outline"></ion-icon></a>
        </div>
        <div class="videos-list">

            <?php foreach ($this->videoData as $key => $video): ?>
                <div class="video">
                    <video width="250" height="150" controls>
                        <source src="<?=$SITE_URL?>files/uploads/<?=$video['video']?>" type="video/mp4">
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
                        <div class="video-hints">
                            <?php if($video['quality']): ?>
                            <div class="better-quality">
                                <img src="<?=$SITE_URL?>templates/default/free-icon-high-quality-7479467.png" style="width: 100%">
                            </div>
                            <?php endif; ?>
                            <?php if($video['commentary']): ?>
                            <div class="typhlocommentary" onclick="PlaySound('mySound')" style="cursor: pointer;">
                                <audio id="mySound" src="../../../templates/default/assets/sounds/tts_audio_convert_650d613e7e0b6350568312.mp3"></audio>
                                <img src="<?=$SITE_URL?>templates/default/free-icon-sound-3293603.png" style="width: 100%">
                            </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


        </div>
</section>
<script>
    function PlaySound(soundobj) {
        var thissound = document.getElementById(soundobj);
        thissound.play();
    }

    function StopSound(soundobj) {
        var thissound = document.getElementById(soundobj);
        thissound.pause();
        thissound.currentTime = 0;
    }
</script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/footer.php';
?>
