<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>НейрON</title>
    <link rel="stylesheet" href="/src/templatesates/default/index.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />

    <link rel="shortcut icon" type="image/x-icon" href="/src/templatesates/default/icon.png" />
          <!-- Vendor CSS Files -->
  <link href="/src/templates/default/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/src/templates/default/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/src/templatesates/default/assets/css/style.css" rel="stylesheet">

</head>
<body>
<header>
    <div class="header-line">
        <div class="logo-bg">
            <span class="logo-text">дорожный</span>
            <span class="logo-text">контроль</span>
        </div>
        <div style="color: white; font-size:30px;">
        НейрON
        </div>
        <nav style="margin-right: 50px;">
            <ul>
                <li>
                    <input type="file" id="head-upload" hidden>
                    <label for="head-upload" style="font-size:22px;">Загрузка</label>
                </li>
                <li>
                    <a href="#map" style="font-size:22px;">Карта</a>
                </li>
                <li>
                    <a href="#anal" style="font-size:22px;"  >Аналитика</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<main>
    <section class="container">
        <input type="file" id="file-upload" hidden onchange="">
        <!-- <label for="file-upload" class="upload-file">
            <div class="btn">
                <span class="noselect">
                    <ion-icon name="cloud-upload-outline"></ion-icon>
                    Загрузить
                </span>
            </div>
        </label> -->
            <ol>
                <li>Выбоина</li>
                <li>Аллигаторная трещина</li>
                <li>Поперечная трещина</li>
                <li>Продольная трещина</li>
            </ol>
    </section>
</main>

<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<div id="map" style = "width:1200px; height:800px;">
    <div class="filters">
        <div class="filter">
            <label for="Выбоина"><input type="checkbox" id="type_1" checked class="checkbox" rel="яма">Выбоина</label>
        </div>
        <div class="filter">
            <label for="Аллигаторная трещина"><input type="checkbox" id="type_2" checked class="checkbox" rel="скол">Аллигаторная трещина</label>
        </div>
        <div class="filter">
            <label for="Поперечная трещина"><input type="checkbox" id="type_3" checked class="checkbox" rel="скол">Поперечная трещина</label>
        </div>
        <div class="filter">
            <label for="Продольная трещина"><input type="checkbox" id="type_4" checked class="checkbox" rel="скол">Продольная трещина</label>
        </div>
        <select id='date_filter'>
            <?php
            foreach($this->dates as $date) {
                echo "<option value='{$date['date']}'>{$date['date']}</option>";
            }
            ?>
        </select>
    </div>

</div>







<!--<div style="width: 100%"><iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=1%20Babakina%20Street,%20Khimki,%20Russia+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.maps.ie/population/">Population mapping</a></iframe></div>-->
<!-- <script src="/templates/default/index.js"></script> -->

<script>
const file_upload = document.getElementById('file-upload')
const filter1 = document.getElementById('type_1')
const filter2 = document.getElementById('type_2')

const filters = document.querySelectorAll('.checkbox')

const date_filter = document.getElementById('date_filter')

const map = L.map('map', {
    center: [55.75185, 37.61169],
    zoom: 8
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);

let type_1_icon = L.icon({
    iconUrl: '/templates/default/icon-type1.png',
    iconRetinaUrl: '/templates/default/icon-type1.png',
    iconSize: [38, 38],
    iconAnchor: [30, 30],
    // popupAnchor: [-3, -76],
});
let type_2_icon = L.icon({
    iconUrl: '/templates/default/icon-type2.png',
    iconRetinaUrl: '/templates/default/icon-type2.png',
    iconSize: [38, 38],
    iconAnchor: [30, 30],
});
let type_3_icon = L.icon({
    iconUrl: '/templates/default/icon-type3.png',
    iconRetinaUrl: '/templates/default/icon-type3.png',
    iconSize: [38, 38],
    iconAnchor: [30, 30],
});
let type_4_icon = L.icon({
    iconUrl: '/templates/default/icon-type4.png',
    iconRetinaUrl: '/templates/default/icon-type4.png',
    iconSize: [38, 38],
    iconAnchor: [30, 30],
});
var OpenStreetMap_BZH = L.tileLayer('https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles courtesy of <a href="http://www.openstreetmap.bzh/" target="_blank">Breton OpenStreetMap Team</a>',
    bounds: [[46.2, -5.5], [50, 0.7]]
});
var OpenStreetMap_HOT = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
});
var CyclOSM = L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});

const markers = [
    <?php 
        foreach($this->markers as $key => $marker) {
            echo "{
                marker_id: $key,
                marker_coordsX: {$marker['marker_coords_x']},
                marker_coordsY: {$marker['marker_coords_y']},
                iconType: {$marker['type']}_icon,
                note: '{$marker['note']}',
                type: '{$marker['type']}',
                date: '{$marker['date']}',
            },";
        }    
    ?>
]
let baseMaps = {
    'Open Street Map': OpenStreetMap_BZH,
    'Water Color': OpenStreetMap_HOT,
    'Terrain': CyclOSM
}


// let layerControl = L.control.layers().addTo(map);
L.control.layers(baseMaps).addTo(map)


let markers_array = []
markers.forEach(item => {
    if(item.date == date_filter.value) {
        var marker = L.marker([item.marker_coordsX, item.marker_coordsY], {icon: item.iconType})
        //marker.bindPopup(item.note).openPopup()
        marker.bindPopup("<img width='200px'; height='200px'; src=" + item.type + "'/src/templates/default/.jpg'><br>" + "<div style='font-size: 18px;'>" + item.note + "</div>").openPopup()
        markers_array.push(marker)
    }
})
for (let i = 0; i < markers_array.length; i++) {
    map.addLayer(markers_array[i])
}

filters.forEach(box => {
    box.onchange = (event) => {
        // очистить всю карту нахуй
        for (let i = 0; i < markers_array.length; i++) {
            map.removeLayer(markers_array[i])
        }  
        markers_array = []
        filters.forEach(activ_box => {
            if(activ_box.checked) {
                // добавить точки определенного типа
                for (let i = 0; i < markers.length; i++) {
                    if(markers[i].type == activ_box.id && markers[i].date == date_filter.value) {
                        var marker = L.marker([markers[i].marker_coordsX, markers[i].marker_coordsY], {icon: markers[i].iconType})
                        marker.bindPopup("<img width='200px'; height='200px'; src=" + markers[i].type + "'/src/templates/default/.jpg'><br>" + "<div style='font-size: 18px;'>" + markers[i].note + "</div>").openPopup()
                        map.addLayer(marker)
                        markers_array.push(marker)
                    }
                }   

            }
        })
    }
})


date_filter.onchange = (event) => {
    // очистить всю карту нахуй
    for (let i = 0; i < markers_array.length; i++) {
            map.removeLayer(markers_array[i])
        }  
        markers_array = []
        filters.forEach(activ_box => {
            if(activ_box.value) {
                // добавить точки определенного типа
                for (let i = 0; i < markers.length; i++) {
                    if(markers[i].type == activ_box.id && markers[i].date == date_filter.value) {
                        var marker = L.marker([markers[i].marker_coordsX, markers[i].marker_coordsY], {icon: markers[i].iconType})
                        marker.bindPopup("<img width='200px'; height='200px'; src=" + markers[i].type + "'/src/templates/default/.jpg'><br>" + "<div style='font-size: 18px;'>" + markers[i].note + "</div>").openPopup()
                        map.addLayer(marker)
                        markers_array.push(marker)
                    }
                }   

            }
        })
}
</script>

<div id='anal'></div>
<?php 
        $ch_1_type_1 = "";
        $ch_1_type_2 = "";
        $ch_1_type_3 = "";
        $ch_1_type_4 = "";
        $ch_1_date = "";
        foreach($this->chart_main as $key => $value){
            $ch_1_date .= "'$key', ";
            $ch_1_type_1 .= "{$value['type_1']}, ";
            $ch_1_type_2 .= "{$value['type_2']}, ";
            $ch_1_type_3 .= "{$value['type_3']}, ";
            $ch_1_type_4 .= "{$value['type_4']}, ";
        }
        $ch_1_date = substr($ch_1_date,0,-2);
        $ch_1_type_1 = substr($ch_1_type_1,0,-2);
        $ch_1_type_2 = substr($ch_1_type_2,0,-2);
        $ch_1_type_3 = substr($ch_1_type_3,0,-2);
        $ch_1_type_4 = substr($ch_1_type_4,0,-2);
        ?>



<main id="main" style="margin-left: 50px;" class="main">

    
      <div class="row">


<div class="col-lg-6">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title">Общие показатели</h5>

        <!-- Column Chart -->
        <div id="columnChart"></div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#columnChart"), {
            series: [{
                name: 'Выбоины',
                data: [<?=$ch_1_type_1?>]
            }, {
                name: 'Аллигаторная трещина',
                data: [<?=$ch_1_type_2?>]
            }, {
                name: 'Поперечная трещина',
                data: [<?=$ch_1_type_3?>]
            },{
                name: 'Продольная трещина',
                data: [<?=$ch_1_type_4?>]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: [<?=$ch_1_date?>],
            },
            yaxis: {
                title: {
                text: 'Кол-во'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                formatter: function(val) {
                    return val
                }
                }
            }
            }).render();
        });
        </script>
        <!-- End Column Chart -->

    </div>
    </div>
</div>




<div class="col-lg-6">
          <div class="card">
<div class="card-body">
    <h5 class="card-title">Выбоины</h5>

    <!-- Line Chart -->
    <div id="lineChart1"></div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#lineChart1"), {
        series: [{
            name: "Desktops",
            data: [<?=$ch_1_type_1?>]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
            enabled: false
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
            },
        },
        xaxis: {
            categories: [<?=$ch_1_date?>],
        }
        }).render();
    });
    </script>
    <!-- End Line Chart -->

</div>
</div>
</div>


<div class="col-lg-6">
          <div class="card">
<div class="card-body">
    <h5 class="card-title">Аллигаторная трещина</h5>

    <!-- Line Chart -->
    <div id="lineChart2"></div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#lineChart2"), {
        series: [{
            name: "Desktops",
            data: [<?=$ch_1_type_2?>]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
            enabled: false
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
            },
        },
        xaxis: {
            categories: [<?=$ch_1_date?>],
        }
        }).render();
    });
    </script>
    <!-- End Line Chart -->

</div>
</div>
</div>




<div class="col-lg-6">
          <div class="card">
<div class="card-body">
    <h5 class="card-title">Поперечная трещина</h5>

    <!-- Line Chart -->
    <div id="lineChart3"></div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#lineChart3"), {
        series: [{
            name: "Desktops",
            data: [<?=$ch_1_type_3?>]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
            enabled: false
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
            },
        },
        xaxis: {
            categories: [<?=$ch_1_date?>],
        }
        }).render();
    });
    </script>
    <!-- End Line Chart -->

</div>
</div>
</div>



<div class="col-lg-6">
          <div class="card">
<div class="card-body">
    <h5 class="card-title">Продольная трещина</h5>

    <!-- Line Chart -->
    <div id="lineChart4"></div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#lineChart4"), {
        series: [{
            name: "Desktops",
            data: [<?=$ch_1_type_4?>]
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
            enabled: false
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'straight'
        },
        grid: {
            row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
            },
        },
        xaxis: {
            categories: [<?=$ch_1_date?>],
        }
        }).render();
    });
    </script>
    <!-- End Line Chart -->

</div>
</div>
</div>


</div>

</main>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="/src/templates/default/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/src/templates/default/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/src/templates/default/assets/vendor/chart.js/chart.umd.js"></script>
<script src="/src/templates/default/assets/vendor/echarts/echarts.min.js"></script>
<script src="/src/templates/default/assets/vendor/quill/quill.min.js"></script>
<script src="/src/templates/default/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/src/templates/default/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/src/templates/default/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>
</html>