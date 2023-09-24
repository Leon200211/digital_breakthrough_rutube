# <p align="center"> ЦИФРОВОЙ ПРОРЫВ: СЕЗОН ИИ </p>
# <p align="center"> УЛУЧШЕНИЕ КАЧЕСТВА ВИДЕО SUPER-RESOLUTION </p>

![image](https://github.com/Leon200211/digital_breakthrough_rutube/assets/91278041/698e841b-cdcc-4875-ae8d-ad3d3b6a4963)




*Состав команды "нейрON"*   
*Чиженко Леон (https://github.com/Leon200211) - Backend-разработчик*    
*Сергей Куликов (https://github.com/MrMarvel) - Backend-разработчик*  
*Карпов Даниил (https://github.com/Free4ky) - ML-engineer/MLOps*  
*Валуева Анастасия (https://github.com/VoLuIcHiK) - Team Lead/Designer/ML-engineer*   
*Козлов Михаил (https://github.com/Borntowarn) - ML-engineer/MLOps*  

## Оглавление
1. [Задание](#1)
2. [Решение](#2)
3. [Результат разработки](#3)
4. [Уникальность нашего решения](#5)
5. [Стек](#6)
6. [Развертывание и тестировани](#7)
7. [Ссылки](#9)

## <a name="1"> Задание </a>

В данном соревновании RUTUBE предлагает разработать систему улучшения качества видео с целью повышения привлекательности видеохостинга для пользователей.
С учетом большой доли User Generated Content, представляющую собой разнообразие видео от пользователей платформы с разным качеством съемки, важным аспектом становится внедрение технологии автоматического улучшения разрешения видео, устраняющую шумы, сжатия, размытия и прочие дефекты видео.
Подобная задача решается методами машинного обучения, которые с каждым годом прогрессируют и показывают все большее качество как на видео, так и на изображениях.

## <a name="2">Решение </a>

Ниже представлен алгоритм работы ML-части нашего приложения, а также взаимодействие RabbitMQ, обработчика и модели: 

![image](https://github.com/Leon200211/digital_breakthrough_rutube/assets/91278041/ac6e2920-b7aa-4ff4-a409-3f4b7925f54c)


Как видно из схемы, обработчик (в данном случае один) постоянно отслеживает появление нового пути к видео во входной очереди (input). Очереди (input и output) были реализованы с помощью сервиса RabbitMQ. Как только обработчик обнаруживает путь во входной очереди - он получает видео по указанному пути, проводит предобработку и отправляет данные в модель, которая развернута на Triton Server. Обработчик ждет, пока модель вернет ему результат обработки. После получения улучшенных кадров обработчик проводит постобработку, загружает видео в хранилище и добавляет новый путь в выходную очередь. 
Все параметры сервиса масштабируемы, поэтому скорость обработки можно увеличить путем добавления дополнительных экземпляров обработчиков и моделей (это один из параметров запуска). 
В предобработку входит деление на кадры, а в постобработку - сбор ролика из улучшенных кадров, а также проверка на соотвествие требуемой размерности (480).
Использование Triton Server повышает эффективность работы GPU и делает вывод намного экономически эффективнее. На сервере входящие запросы упаковываются в пакеты и отправляются для вывода. Таким образом, пакетная обработка позволяет эффективнее использовать ресурсы GPU.
Для ускорения работы модели был использован TensorRT, который позволил увеличить скорость обработки моделью до 40 раз быстрее чем на CPU и до 5 раз быстрее чем обычный запуск на GPU!

| Low Resolution кадр  | High  Resolution кадр |
| ------------- | ------------- |
| ![image](https://github.com/Leon200211/digital_breakthrough_rutube/assets/91278041/e760165e-f2dc-41e0-83d5-cb5166551e0d) | ![image](https://github.com/Leon200211/digital_breakthrough_rutube/assets/91278041/ab13713e-bf73-41d9-818f-e9bb9e94672e)


Если сравнить эти кадры, невооруженным глазом заметно, что модель убрала шумы на заднем плане, улучшила изображение слонов, лица молодного человека, а также в целом стали лучше видны мелкие детали в одежде, фоне. Качество стало значительно лучше.


## <a name="3">Результат разработки </a>

В ходе решения поставленной задачи нам удалось реализовать *рабочий* прототип со следующими компонентами:
1. Сайт, похожий на RUTUBE Studio, который отражает наше видение интеграции опции "Улучшить качество видео". Для удобства пользователя был сделать прогресс бар;
2. Сконвертированная в TensorRT модель REAL-ESRGAN, которая была дообучена на тренировочном датасете;
3. Очереди RabbitMQ для асинхронной обработки;
4. Triton Server, на котором развернута модель;
5. Обработчики - связующее звено между моделью и очередями.


Также мы предусмотрели использование нашей программы на очереди из видео, что позволит использовать ее для быстрой обработки болььшого количества видео. 
Наша модель REAL-ESRGAN позволяет в будущем добавить обработку в real time благодаря использованию ffmpeg и обработке видео с высоким FPS - 30-40 FPS.
Помимо этого мы задумались о решение социальной проблемы - генерации тифлокомментариев для слабовидящих людей. Мы написали код, который позволит генерировать описание сцен, озвучивать его и накладывать на видео. (данный код представлен в архиве на гугл диске)


## <a name="5">Уникальность нашего решения </a>
- Реализация очередей с помощью контейнера RabbitMQ;
- Развертыввание модели на Triton Server;
- Ускорение работы за счет использования TensorRT;
- Высокая скорость работы модели - 40% от длительности видео;
- Отличное соотношение время/качество работы;
- Готовый для масштабирования и интеграции прототип;
- Потенциал использования для обработки в real time;
- Дополнительно: генерация тифлокомментариейв для слабовидящих людей.


## <a name="6">Стек </a>

![image](https://github.com/Leon200211/digital_breakthrough_rutube/assets/91278041/74c3a0eb-b913-4f4d-bd2e-a78863d230d5)


## <a name="7">Развертывание и тестирование </a>
Проект содержит в себе 4 сервиса:
1. Фронтенд в виде разработанного сайта (папка `site`)
2. RabbitMQ для асинхронной связи с обработчиками (отдельный `image` в `copmose`)
3. Бэкенд для взамодействия с хранилищем видео и сайтом (папка `backend`)
4. Сервер для оптимизированного инференса моделей Triton Server

Скачивание образов может занять какое то время (10-20 минут), поэтому первый запуск может занимать продолжительное время.

:warning: 
Скорость обработки напрямую будет зависеть от количества инстансов в Triton и количества реплик обработчиков. В текущем варианте запуска используется 1 обработчик и 1 инстанс. Для изменения количества экземпляров необходимо:
1. Для обработчика. В `compose.yml` в разделе adapter увеличить значение `replicas`
2. Для модели. В папке `tensorrt_models_running` в `config.pbtxt` изменить значение `count` в разделе `instance_group`

### Последовательность действий для запуска (желательно развертывать на Linux):

1. Склонировать репозиторий 
```Bash
git clone https://github.com/Leon200211/digital_breakthrough_rutube.git
```
2. Для того, чтобы модели запустились на вашей GPU необходимо предварительно произвести их конвертацию в TensorRT (см. [инструкцию](https://github.com/VoLuIcHiK/super-resolution/tree/main/model_convertation#readme)). Поcле этого просто замените файл `model.plan` в [папке](https://github.com/VoLuIcHiK/super-resolution/tree/main/tensorrt_models_running/real_esrganx4_fp16_tensorrt/1) на новый
3. В папке проекта выполнить команду 
```Bash
docker-compose -f devops/compose.yml up --build -d
```
4. Зайти на [сайт](http://localhost:8008/) (разворачивается локально на порту 8008).
5. Загрузить видео и запустить обработку нажатием кнопки. При upscale x4 видео обрабатывается в 75% от длительности, а при x2 в 40% соответственно  

## <a name="9">Ссылки</a>
- [Гугл диск с материалами](https://drive.google.com/drive/folders/1dJfBBPN-eLbLK-rgtZ2S7EVrKKa_5ftp?usp=sharing)
- [Дообучение RealESRGAN](https://github.com/xinntao/Real-ESRGAN/blob/master/docs/Training.md)


