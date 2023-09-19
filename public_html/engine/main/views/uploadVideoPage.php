<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/header.php';
?>



<section>
    <div class="container">
        <div class="section-title">
            Загрузка видео
        </div>
        <div class="toVidList">
            <a href="/"><ion-icon name="arrow-back-outline"></ion-icon> Назад</a>
        </div>
        <div class="upload-form">
            <div class="progressBars">
                <div class="progressBar">
                    <div class="itemBar">
                        <div class="label"></div>
                    </div>
                    <ion-icon name="checkmark-circle-outline" id="load"></ion-icon>
                </div>
                <div class="progressBar" id="process-progressBar">
                    <div class="itemBar" id="process-itemBar">
                        <div class="label" id="process-label"></div>
                    </div>
                    <ion-icon name="checkmark-circle-outline" id="process"></ion-icon>
                </div>
            </div>


            <form class="form" id="uploadForm" method="POST" enctype="multipart/form-data" action="upload.php">
                <input type="file" id="inpFile" class="inpFile" name="inpFile" hidden>
                <label for="inpFile" id="inpFile">Выберите видео</label>
                <button type="submit">
                    Загрузить
                </button>
            </form>
            <div class="video-info" style="display: none">
                <div class="video-frame">
                    <div class="frame-animation">
                        <ion-icon name="reload-outline"></ion-icon>
                    </div>
                </div>
                <video width="400" height="250" controls style="border-radius: 15px" hidden>
                    <source src="uploads/skyrimTest.mp4" type="video/mp4">
                </video>
                <div class="info-inputs">
                    <input type="text" class="video-name" placeholder="Введите название...">
                    <textarea name="" id="" cols="30" rows="10" class="video-desc" placeholder="Введите описание..."></textarea>
                </div>
                <div class="video-checkbox">
                    <div class="checkbox">
                        <input type="checkbox" id="vid-quality">
                        <label for="vid-quality">Улучшить качество</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" id="vid-quality2">
                        <label for="vid-quality2">Улучшить качество 2</label>
                    </div>
                </div>
                <div class="save">
                    <a href="index.php">Сохранить и выйти</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const uploadForm = document.querySelector('#uploadForm')
    const inpFile = document.querySelector('#inpFile')
    let elem = document.querySelector('.itemBar')
    const rootDiv = document.querySelector('.container')


    uploadForm.onsubmit = (e) => {
        e.preventDefault();
        const files = document.querySelector('[name=inpFile]').files
        const formData = new FormData()
        formData.append('avatar', files[0])
        const xhr = new XMLHttpRequest()
        xhr.responseType = 'json'


        xhr.open('POST', '/uploadVideo')
        // document.createElement('div')
        xhr.upload.addEventListener('progress', e => {
            const percent = e.lengthComputable ? (e.loaded / e.total) * 100 : 0;
            console.log(e.loaded)
            console.log(e.total)
            elem.style.width = percent.toFixed(2) + '%'
            document.querySelector('.label').textContent = percent.toFixed(2) + '%'

        })
        xhr.onload = () => {
            document.querySelector('.video-info').style.display = 'flex'
            document.querySelector('form').setAttribute('hidden', true)
            document.querySelector('#load').style.color = 'green'
            document.querySelector('#process-itemBar').style.width = '100%'
            document.querySelector('#process').style.color = 'green'
            document.querySelector('#process-label').textContent = 'Обработка...'
            setTimeout(() => {
                document.querySelector('.video-frame').style.display = 'none'
                document.querySelector('video').removeAttribute('hidden')
            }, 800)

            let responseObj = xhr.response;
            console.log(responseObj); // Привет, мир!
        }
        xhr.send(formData)
    }


</script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/footer.php';
?>