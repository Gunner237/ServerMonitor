<?php
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)].'B';
    }
    ?>
<html>
<head>
<title>
Server Status
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/asPieProgress.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/jquery-asPieProgress.min.js"></script>
<style type="text/css">
body {
    /*padding-top: 60px;*/
}
.pie_progress {
width: 160px;
margin: 10px auto;
}
@media all and (max-width: 768px) {
    .pie_progress {
    width: 80%;
        max-width: 300px;
    }
}
pre{
height: 250px;
overflow: scroll;
}
.title{
height: 50px;
}
</style>
</head>
<body class="text-light bg-dark">
<nav class="navbar navbar-dark bg-primary mb-5">
<a class="navbar-brand" href="#">Server Monitor v0.2</a>
<div id="navbarText">
<ul class="navbar-nav mr-auto">
</ul>
<span class="navbar-text">
<?php echo ''.gethostname().'@'.$_SERVER['SERVER_ADDR'].'';?>
</span>
</div>
</nav>
<div class="container">
<div class="row">
<div class="col-xs col-sm-4 col-md col-lg" id="cpuDiv">
<div class="pie_progress_cpu" role="progressbar" data-goal="33">
<div class="pie_progress__number">0%</div>
<div class="pie_progress__label">CPU</div>
</div>
<h3 style="text-align:center;">CPU</h3>
<div style="text-align:center;" class='title'>i7 4770</div>
</div>
<div class="col-xs col-sm-4 col-md col-lg" id="memDiv">
<div class="pie_progress_mem" role="progressbar" data-goal="33">
<div class="pie_progress__number">0%</div>
<div class="pie_progress__label">Memory</div>
</div>
<h3 style="text-align:center;">Memory</h3>
<div style="text-align:center;" class='title'>16GB</div>
</div>
<div class="col-xs col-sm-4 col-md col-lg" id="diskDiv">
<div class="pie_progress_disk" role="progressbar" data-goal="33">
<div class="pie_progress__number">0%</div>
<div class="pie_progress__label">Disk</div>
</div>
<h3 style="text-align:center;">Disk</h3>
<div style="text-align:center;" class='title'><?php echo formatBytes(disk_total_space('/'));?></div>
</div>
<div class="col-xs col-sm-4 col-md col-lg" id="temperatureDiv">
<div class="pie_progress_temperature" role="progressbar" data-goal="0">
<div class="pie_progress__number">0°C</div>
<div class="pie_progress__label">Temperature</div>
</div>
<h3 style="text-align:center;">Temperature</h3>
<div style="text-align:center;" class='title'></div>
</div>
</div>
</div>
<hr style="color:white;background:white;">
<h3 class="container">Verbose data</h3>
<div id='data' class="container">

</div>
</div>

<script>
function decimalplace(input){
    if (input >= 10){
        return Math.round(input*10)/10;
    }
    else{
        return Math.round(input*100)/100;
    }
}
$(document).ready(function () {
       $('.pie_progress_temperature').asPieProgress({
                                                                                                             min: 0,
                                                                                                             max: 100,
                                                                                                             goal: 100,
                                                                                                             numberCallback(n) {
                                                                                                             'use strict';
                                                                                                             const percentage = Math.round(this.getPercentage(n));
                                                                                                             return `${percentage}°C`;
                                                                                                             },
                                                    barcolor: '#428bca',
                                                    trackcolor: '#f2f2f2',
                                                                                                             
                  });
                  $('.pie_progress_cpu, .pie_progress_mem, .pie_progress_disk').asPieProgress({
                                                                                                                        min: 0,
                                                                                                                        max: 100,
                                                                                                                        goal: 100,
                                                                                                                        numberCallback(n) {
                                                                                                                        'use strict';
                                                                                                                        const percentage = decimalplace(this.getPercentage(n));
                                                                                                                        return `${percentage}%`;
                                                                                                                        },
                                                                                              barcolor: '#428bca',
                                                                                              trackcolor: '#f2f2f2',
                                                                                                                        
                                                                                                                        });
       });


function loadlink(){
    $('#data').load('data.php',function () {
                     $(this).unwrap();
                     });
}
loadlink(); // This will run on page load
setInterval(function(){
            
            $.get('data/cpu.php', function(data) {
                  cpudata = data;
                  });
            $('.pie_progress_cpu').asPieProgress("go",cpudata);
            
            $.get('data/mem.php', function(data) {
                  memdata = data;
                  });
            $('.pie_progress_mem').asPieProgress("go",memdata);
            
            $.get('data/disk.php', function(data) {
                  diskdata = data;
                  });
            $('.pie_progress_disk').asPieProgress("go",diskdata);
            
            $.get('data/temp.php', function(data) {
                  tempdata = data;
                  });
            $('.pie_progress_temperature').asPieProgress("go",tempdata);
            
            $.get('data/disk.php', function(data) {
                  diskdata = data;
                  });
            $('.pie_progress_disk').asPieProgress("go",diskdata);
            
            loadlink();
            }, 1000);
</script>


</body>
</html>
