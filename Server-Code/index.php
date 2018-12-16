
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Testanwendung</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/solid.css" integrity="sha384-aj0h5DVQ8jfwc8DA7JiM+Dysv7z+qYrFYZR+Qd/TwnmpDI6UaB3GJRRTdY8jYGS4" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/regular.css" integrity="sha384-l+NpTtA08hNNeMp0aMBg/cqPh507w3OvQSRoGnHcVoDCS9OtgxqgR7u8mLQv8poF" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/fontawesome.css" integrity="sha384-WK8BzK0mpgOdhCxq86nInFqSWLzR5UAsNg0MGX9aDaIIrFWQ38dGdhwnNCAoXFxL" crossorigin="anonymous">
    <style>
        body {
            padding-top: 5rem;
        }
        .starter-template {
            padding: 3rem 1.5rem;
            text-align: center;
        }
    </style>
</head>

<body>

<nav class="container navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Testanwendung</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>

<main role="main" class="container">

    <div class="starter-template">
        <h1>Testanwendung</h1>
        <p class="lead">Wähle eine Station, das Datum und die anzuzeigende Zeitperiode aus.</p>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row center">
                <div class="col-4">

                </div>
            </div>

            <div class="row ">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">



                            <div class="float-right">
                                <label for="period">Intervall</label>
                                <select id="period" class="form-control">
                                    <option value="day">Tag</option>
                                    <option value="month">Monat</option>
                                    <option value="year">Jahr</option>
                                </select>
                            </div>

                            <div class="float-right" style="padding-right: 20px">
                                <label for="date-picker">Datum</label>
                                <input name="date-picker" id="date-picker" class="form-control" />
                            </div>

                            <div class="float-right" style="padding-right: 20px">
                                <label for="stations">Station</label>
                                <select class="form-control" name="stations" id="stations">

                                </select>
                            </div>

                        </div>


                        <div class="card-body p-0">
                            <div class="w-100 p-4 mw-100 d-inline-block">
                                <div class="row">
                                    <div class="col-3"><b>Aktuelle Werte:</b> <br> <span id="timestamp"></span></div>
                                    <div class="col-3">Temperatur: <span id="temp-now"></span></div>
                                    <div class="col-3">Luftdruck: <span id="press-now"></span></div>
                                    <div class="col-3">Luftfeuchte: <span id="humid-now"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="w-100 p-4 mw-100 d-inline-block">
                                <div class="row">
                                    <div class="col-3"><b>Trend (1 Stunde):</b></div>
                                    <div class="col-3">Temperatur: <span id="temp-trend"><i class="far fa-question-circle"></i></span></div>
                                    <div class="col-3">Luftdruck: <span id="press-trend"><i class="far fa-question-circle"></i></span></div>
                                    <div class="col-3">Luftfeuchte: <span id="humid-trend"><i class="far fa-question-circle"></i></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">

                            <div class="w-100 p-4 mw-100 d-inline-block">
                                <div id="chartdiv"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

</main><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- Graphs -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.de.min.js"></script>

<script>
    var data;
    $(document).ready(function() {
        $('#chartdiv').height(
            $(window).height() - ($(window).height() / 2)
        );

        $('#date-picker').datepicker( {
            format: "dd.mm.yyyy",
            language: "de",
            autoclose: true
        }).on("changeDate", function(e) {
            reload();
        });

        $('#date-picker').datepicker("setDate", new Date());
        $("#period").val("day");

        var $select = $('#stations');

        $.ajax({
            dataType: "json",
            url: 'api/get_data.php?action=get_now',
            success: function (data) {
                $.each(data, function(i, val){
                    $select.append($('<option />', { value: val.station.id, text: val.station.name }));
                });
                reload();
                loadStatus();
            }
        });

    });

    var period = 'day';

    var chart = AmCharts;


    $('#period').on('change', function() {
        reload();
    });

    $('#stations').on('change', function() {
        reload();
        loadStatus();
    });

    setInterval(function(){
        reload();
        loadStatus();
    }, 60000);

    function makeChart(dataProvider) {
        chart = AmCharts.makeChart("chartdiv", makeOptions(dataProvider))
    }


    function makeOptions(dataProvider) {
        return {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": dataProvider,
            "mouseWheelZoomEnabled": true,
            "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
            "synchronizeGrid": true,
            "valueAxes": [{
                "id": "temp",
                "axisColor": "#ff0000",
                "axisThickness": 2,
                "axisAlpha": 1,
                "position": "left"
            }, {
                "id":"pres",
                "axisColor": "#fcf700",
                "axisThickness": 2,
                "axisAlpha": 1,
                "position": "right"
            }, {
                "id":"humi",
                "axisColor": "#45de00",
                "axisThickness": 2,
                "axisAlpha": 1,
                "offset": 50,
                "position": "left"
            }],
            "graphs": [
                {
                    "valueAxis": "temp",
                    "lineColor": "#ff0000",
                    "bullet": "round",
                    "bulletBorderThickness": 1,
                    "hideBulletsCount": 50,
                    "lineThickness": 2,
                    "title": "Temperatur",
                    "valueField": "temperature",
                    "balloonText": "<span style='margin:0px; font-size:12px;'>[[value]] " + "°C" + "</span>"
                },
                {
                    "valueAxis": "pres",
                    "lineColor": "#fcf700",
                    "bullet": "square",
                    "hideBulletsCount": 50,
                    "lineThickness": 2,
                    "title": "Luftdruck",
                    "valueField": "pressure",
                    "balloonText": "<span style='margin:0px; font-size:12px;'>[[value]] " + "hPa" + "</span>"
                },
                {
                    "valueAxis": "humi",
                    "lineColor": "#45de00",
                    "bullet": "triangleUp",
                    "hideBulletsCount": 50,
                    "lineThickness": 2,
                    "title": "Luftfeuchte",
                    "valueField": "humidity",
                    "balloonText": "<span style='margin:0px; font-size:12px;'>[[value]] " + "%" + "</span>"
                }
            ],
            "chartScrollbar": {
                "graph": 'temp',
                "oppositeAxis":false,
                "offset":30,
                "scrollbarHeight": 80,
                "backgroundAlpha": 0,
                "selectedBackgroundAlpha": 0.1,
                "selectedBackgroundColor": "#888888",
                "graphFillAlpha": 0,
                "graphLineAlpha": 0.5,
                "selectedGraphFillAlpha": 0,
                "selectedGraphLineAlpha": 1,
                "autoGridCount":true,
                "color":"#AAAAAA"
            },
            "chartCursor": {
                "categoryBalloonDateFormat": "JJ:NN, DD MMMM"
            },

            "categoryField": "time",
            "categoryAxis": {
                "minPeriod": "ss",
                "parseDates": true,
                "axisColor": "#DADADA",
                "minorGridEnabled": true
            },

            "export": {
                "enabled": true
            }
        };
    }

    function reload() {
        var date = $('#date-picker').datepicker('getDate');
        if (date == null) {
            date = formatDate(new Date());
        } else {
            date = formatDate(date);
        }

        console.log(date);
        var period2 =  $( "#period" ).val();
        console.log(period2);
        var stationID = $( "#stations" ).val();
        console.log(stationID);

        $.ajax({
            dataType: "json",
            url: 'api/get_data.php?action=get_now&date='+date,
            success: function (data) {
                $.each(data, function(i, val){
                    if (val.station.id === stationID) {
                        makeChart(val[period2])
                    }
                });
            }
        });
    }

    function loadStatus() {
        var stationID = $( "#stations" ).val();
        console.log(stationID);

        $.ajax({
            dataType: "json",
            url: 'api/get_data.php?action=get_status',
            success: function (data) {
                $.each(data, function(i, val){
                    if (val.station.id === parseInt(stationID)) {
                        console.log(val);
                        displayStatus(val)
                    }
                });
            }
        });
    }

    function displayStatus(val) {

        $('#temp-now').text(val.latest.temp + ' °C') ;
        $('#humid-now').text(val.latest.humid + ' %rF') ;
        $('#press-now').text(val.latest.press + ' hPa') ;
        $('#timestamp').text(new Date(val.latest.time).toLocaleString()) ;

        if (val.trend.temp === 'up') {
            $('#temp-trend').children("i").attr("class", "fas fa-arrow-up") ;
        } else if (val.trend.temp === 'down') {
            $('#temp-trend').children("i").attr("class", "fas fa-arrow-down") ;
        } else if (val.trend.temp === '-') {
            $('#temp-trend').children("i").attr("class", "fas fa-arrow-right") ;
        }

        if (val.trend.humid === 'up') {
            $('#humid-trend').children("i").attr("class", "fas fa-arrow-up") ;
        } else if (val.trend.humid === 'down') {
            $('#humid-trend').children("i").attr("class", "fas fa-arrow-down") ;
        } else if (val.trend.humid === '-') {
            $('#humid-trend').children("i").attr("class", "fas fa-arrow-right") ;
        }

        if (val.trend.press === 'up') {
            $('#press-trend').children("i").attr("class", "fas fa-arrow-up") ;
        } else if (val.trend.press === 'down') {
            $('#press-trend').children("i").attr("class", "fas fa-arrow-down") ;
        } else if (val.trend.press === '-') {
            $('#press-trend').children("i").attr("class", "fas fa-arrow-right") ;
        }

    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
</script>


</body>
</html>
