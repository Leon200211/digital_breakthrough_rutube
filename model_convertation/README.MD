# Инструкция по конвертации моделей в TensorRT и запуску их в Triton Server

Из-за того, что архитектура видеоускорителей сильно отличается, модель, преобразованная на 1 GPU не может быть запущена на другой GPU из-за несовместимости операций. Поэтому для каждой модели GPU необходимо проводить отдельную конвертацию.

## Наши модели

1. В папке ```weights``` лежат веса сконвертированных в onnx моделей:
-  baseline_fp16 - Архитектура SRCNN. Тип весов fp16.
-  finetunedx2_fp16 - RealESRGAN дообученный на тренировочном датасете. Тип весов fp16. Увеличение размерности x2.
- real_esrganx2_fp16 - RealESRGAN предобученный.Тип весов fp16. Увеличение размерности x2.
- real_esrganx4_fp16 - RealESRGAN предобученный.Тип весов fp16. Увеличение размерности x4.

:bangbang: При конвертиции используется большое количество памяти GPU:
1. Для `real_esrganx4_fp16` необходимо ~17gb
2. Для `real_esrganx2_fp16` и `finetunedx2_fp16` необходимо ~6gb
3. Для `baseline_fp16` необходимо ~1gb

## Шаги по конвертации

1. Запустить docker-compose:
```Bash
docker-compose -f devops/convertation_compose.yml run --rm vsgan_tensorrt
```
2. Внутри контейнера будет примонтирован volume с ONNX моделями. 
Для того, чтобы сконвертировать какую либо из них необходимо запустить команду
(можно выбрать модели из папки [/weights](https://github.com/VoLuIcHiK/super-resolution/tree/main/weights)):

```Bash
trtexec --fp16 --onnx=real_esrganx4_fp16.onnx --minShapes=input:1x3x8x8 --optShapes=input:1x3x720x1280 --maxShapes=input:1x3x1080x1920 --saveEngine=model.plan --tacticSources=+CUDNN,-CUBLAS,-CUBLAS_LT --skipInference
```
Для более слабых видеокарт (<=8 ГБ VRAM):
```bash
trtexec --fp16 --onnx=real_esrganx2_fp16.onnx --minShapes=input:1x3x8x8 --optShapes=input:1x3x720x1280 --maxShapes=input:1x3x1080x1920 --saveEngine=model.plan --tacticSources=+CUDNN,-CUBLAS,-CUBLAS_LT --skipInference
```

:warning: `model.plan` появится в той же папке с весами 
[/weights](https://github.com/VoLuIcHiK/super-resolution/tree/main/weights).

## Шаги по настройки Triton Server

Полученный в предыдущем шаге engine нужно поместить в папку ```tensorrt_models_running```, сохранив следующую структуру:

```
tensorrt_models_running
└── model_tensorrt
    ├── 1
    │   └── model.plan
    └── config.pbtxt
```

В файле config.pbtxt есть возможность указать количество одновременно запускаемых экземпляров и какие GPU использовать:

```yaml
name: "model_tensorrt"
platform: "tensorrt_plan"
max_batch_size: 0
input [
    {
        name: "input"
        data_type: TYPE_FP16
        dims: [-1, 3, -1, -1]
    }
]
output [
    {
        name: "output"
        data_type: TYPE_FP16
        dims: [-1, 3, -1, -1]
    }
]
instance_group [
    {
      count: 1          # Количество инстансов на карте
      kind: KIND_GPU
      gpus: [0]         # Какие GPU использовать
    }
]
```

# [Продолжить настройку](https://github.com/VoLuIcHiK/super-resolution/tree/main?tab=readme-ov-file#%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%D0%B4%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C-%D0%B4%D0%B5%D0%B9%D1%81%D1%82%D0%B2%D0%B8%D0%B9-%D0%B4%D0%BB%D1%8F-%D0%B7%D0%B0%D0%BF%D1%83%D1%81%D0%BA%D0%B0-%D0%B6%D0%B5%D0%BB%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE-%D1%80%D0%B0%D0%B7%D0%B2%D0%B5%D1%80%D1%82%D1%8B%D0%B2%D0%B0%D1%82%D1%8C-%D0%BD%D0%B0-linux)