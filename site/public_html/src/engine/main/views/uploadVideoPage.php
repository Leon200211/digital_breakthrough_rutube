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
                        <div class="label" id="load-label"></div>
                        <div class="label" id="load-label2"></div>
                    </div>
                    <ion-icon name="checkmark-circle-outline" id="load"></ion-icon>
                </div>
                <div class="progressBar" id="process-progressBar">
                    <div class="itemBar" id="process-itemBar">
                        <div class="label" id="process-label"></div>
                        <div class="label" id="process-label2"></div>
                    </div>
                    <ion-icon name="checkmark-circle-outline" id="process"></ion-icon>
                </div>
            </div>
            <div class="thatOneForm">
                <div class="video-info" style="display: none">
                    <div class="video-frame">
                        <div class="frame-animation">
                            <ion-icon name="reload-outline"></ion-icon>
                        </div>
                    </div>
                    <video controls style="border-radius: 15px; width: 100%;height: 250px; max-width: 400px; max-height: 250px" hidden>
                        <source src="" type="video/mp4">
                    </video>
                </div>
<!--                <form class="form" id="uploadForm" method="POST" enctype="multipart/form-data">-->
                    <div class="video-inputs">
                        <div class="select-vid">
                            <input type="file" id="inpFile1" class="inpFile" name="inpFile" hidden>
                            <label for="inpFile1" id="inpFile">Выберите видео</label>
                            <div class="vid-name"></div>
                        </div>

                        <div class="info-inputs">
                            <input type="text" class="video-name" id="video_name" placeholder="Введите название...">
                            <textarea name="" cols="30" rows="10" id="video_description" class="video-desc" placeholder="Введите описание..."></textarea>
                            <div class="video-checkbox">
                                <div class="checkbox">
                                    <input type="checkbox" id="vid-quality">
                                    <label for="vid-quality">Улучшить качество</label>
                                    <span id="better-quality">?</span>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" id="vid-commentary">
                                    <label for="vid-quality2">Добавить тифлокомментарии</label>
                                    <span id="typhlocommentary">?</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="upload_video_btn" style="
                            width: 100%;
                            max-width: 150px;
                            margin-top: 15px;
                            align-self: flex-end;
                        ">
                        Загрузить
                    </button>
<!--                </form>-->
            </div>
        </div>
    </div>
</section>
<script>

    const uploadForm = document.querySelector('#uploadForm')
    const inpFile = document.querySelector('.inpFile')
    let elem = document.querySelector('.itemBar')
    const rootDiv = document.querySelector('.container')

    let subbtn = document.getElementById('upload_video_btn')

    function ext(name) {
        var m = name.match(/\.([^.]+)$/)
        return m && m[1]
    }

    inpFile.onchange = (i) => {
        console.log(1);
        if(inpFile.files.length) {
            let fileName = inpFile.files[0].name
            console.log(fileName)
            var splittedFileName = ext(fileName)
            document.querySelector('.vid-name').style.display = 'inline-table'
            document.querySelector('.vid-name').textContent = fileName
            document.querySelector('.vid-name').style.border = '1px dashed black'
            document.querySelector('.vid-name').style.width = '10%'
            document.querySelector('.vid-name').style.color = 'black'
        }
        if (splittedFileName == 'mp4' || splittedFileName == 'mov' || splittedFileName == 'wmv' ||
            splittedFileName == 'avi' || splittedFileName == 'avchd' || splittedFileName == 'flv' ||
            splittedFileName == 'f4v' || splittedFileName == 'swf' || splittedFileName == 'mkv' ||
            splittedFileName == 'webm') {

            document.getElementById('upload_video_btn').removeAttribute('disabled');
            document.getElementById('upload_video_btn').style.background = '#FF645F';

            subbtn.onclick = (e) => {
                    e.preventDefault();
                    const files = document.querySelector('[name=inpFile]').files[0]
                    const formData = new FormData()
                    formData.append('file', files)

                    console.log(inpFile.files)
                    console.log(inpFile.files[0])
                    console.log(files)


                    formData.append('name', document.getElementById('video_name').value)
                    formData.append('description', document.getElementById('video_description').value)
                    formData.append('quality', document.getElementById('vid-quality').checked)
                    formData.append('commentary', document.getElementById('vid-commentary').checked)

                    console.log(formData)

                    const xhr = new XMLHttpRequest()

                    xhr.open('POST', '/uploadVideo')
                    xhr.upload.addEventListener('progress', e => {
                        const percent = e.lengthComputable ? (e.loaded / e.total) * 100 : 0;
                        elem.style.width = percent.toFixed(2) + '%'
                        document.querySelector('#load-label').textContent = percent.toFixed(2) + '%'
                    })

                    xhr.onload = () => {

                        document.querySelector('#load-label2').textContent = 'Загрузка завершена'
                        document.querySelector('.label').style.color = '#52C78F'
                        document.querySelector('#vid-quality').setAttribute('disabled', true)
                        document.querySelector('#vid-commentary').setAttribute('disabled', true)
                        document.querySelector('.video-name').setAttribute('disabled', true)
                        document.querySelector('.video-desc').setAttribute('disabled', true)
                        document.querySelector('.video-info').style.display = 'flex'
                        document.querySelector('[for="inpFile1"]').style.display = 'none'
                        document.querySelector('.vid-name').style.display = 'none'
                        document.querySelector('[type="submit"]').style.display = 'none'
                        document.querySelector('#load').style.color = '#52C78F'
                        document.querySelector('#process-itemBar').style.width = '100%'
                        document.querySelector('#process').style.color = '#52C78F'
                        document.querySelector('#process-label').style.color = '#52C78F'
                        document.querySelector('#process-label').textContent = 'Обработка...'
                        let JSONobj = JSON.parse(xhr.response)
                        console.log(JSONobj.status)
                        console.log(JSONobj)
                        if (JSONobj.status == 'success') {
                            var xhr2 = new XMLHttpRequest()
                            var formdata2 = new FormData()
                            formdata2.append('id', JSONobj.id)
                            var proccess = setInterval(() => {
                                xhr2.open('POST', '/checkVideo')
                                xhr2.send(formdata2)
                                xhr2.onload = () => {
                                    let JSONobj2 = JSON.parse(xhr2.response)
                                    if (JSONobj2.is_processed == 1) {
                                        clearInterval(proccess)
                                        document.querySelector('#process-label2').textContent = 'Обработка завершена'
                                        document.querySelector('.video-frame').style.display = 'none'
                                        document.querySelector('video').removeAttribute('hidden')
                                        document.querySelector('source').setAttribute('src', `<?=$SITE_URL?>files/uploads/${JSONobj2.video}`)
                                        document.querySelector('video').currentTime = 0
                                        document.querySelector('video').load()
                                    }
                                }
                            }, 2000)
                        }

                        let responseObj = xhr.response;
                        console.log(responseObj); // Привет, мир!
                    }
                    // let JSONobj = JSON.parse(xhr.response)
                    // if(JSONobj.name){}
                    xhr.send(formData)

            }
        } else {
            document.querySelector('.vid-name').style.display = 'inline-table'
            document.querySelector('.vid-name').style.border = 'none'
            document.querySelector('.vid-name').style.width = '100%'
            document.querySelector('.vid-name').style.color = 'red'
            document.querySelector('.vid-name').textContent = 'Загрузите ВИДЕО, файлы другого формата будут очищаться.'
            document.getElementById('upload_video_btn').setAttribute('disabled', true);
            document.getElementById('upload_video_btn').style.background = 'gray';
            if (i.value) {
                try {
                    i.value = '';
                } catch (err) {
                }
                if (i.value) {
                    var form = document.createElement('form'),
                        parentNode = i.parentNode, ref = i.nextSibling;
                    form.appendChild(i);
                    form.reset();
                    parentNode.insertBefore(i, ref);
                }
            }
        }
}


</script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/default/include/footer.php';
?>