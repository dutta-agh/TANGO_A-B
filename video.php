<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('config.xlsx');
 
$sheet = $spreadsheet->getActiveSheet();

$sheetRow = $sheet->getHighestRow();

//echo $sheetRow;
//die();
$video = '';
$next_page_btn_txt = '';
$next_page_btn_a_or_b = '';
$next_page_url = '';
$copyright_url = '';
$timer = 10;
$pair_id = $_GET['pair_id'];
if($pair_id){
    for($i=2;$i<=$sheetRow;$i++){
        if($pair_id == $i){
            //$pair_id = $sheet->getCell('A'.$i)->getValue();
            if($_GET['video'] == 'first'){
                $number = rand(1,2);
                //echo $number;

                if($number == 1){
                    $video = $sheet->getCell('B'.$i)->getValue();
                    $timer = $sheet->getCell('H'.$i)->getValue();
                    //$copyright_url = $sheet->getCell('F'.$i)->getValue();
                }else{
                    $video = $sheet->getCell('C'.$i)->getValue();
                    $timer = $sheet->getCell('I'.$i)->getValue();
                    //$copyright_url = $sheet->getCell('G'.$i)->getValue();
                }
                
                $next_page_url = 'video.php?pair_id='.$pair_id.'&video=last&number='.$number;
                $next_page_btn_txt = 'Przejdź do filmu B';
                $next_page_btn_a_or_b = 'A';
                // $copyright_url = $sheet->getCell('F'.$i)->getValue();
                // $timer = $sheet->getCell('H'.$i)->getValue();
            }else{
                $number = $_GET['number'];

                if($number == 1){
                    $video = $sheet->getCell('C'.$i)->getValue();
                    $timer = $sheet->getCell('I'.$i)->getValue();
                    //$copyright_url = $sheet->getCell('G'.$i)->getValue();
                }else{
                    $video = $sheet->getCell('B'.$i)->getValue();
                    $timer = $sheet->getCell('H'.$i)->getValue();
                    //$copyright_url = $sheet->getCell('F'.$i)->getValue();
                }

                //$video = $sheet->getCell('C'.$i)->getValue();
                $next_page_url = 'vote.php?pair_id='.$pair_id.'&number='.$number;
                $next_page_btn_txt = 'Przejdź do głosowania';
                $next_page_btn_a_or_b = 'B';
                // $copyright_url = $sheet->getCell('G'.$i)->getValue();
                // $timer = $sheet->getCell('I'.$i)->getValue();
            }
    
            break;
        }
    }
}else{
    $next_page_url = 'video.php?pair_id='.rand(2,$sheetRow).'&video=first';
    header('Location: '.$next_page_url);
}

?>


<!doctype html>
<html>
<head>
<title> Witryna internetowa testu jakości streszczeń wideo </title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<style>
    video {
        margin-left: auto;
        margin-right: auto;
        display: block;
        pointer-events: none;
    }

    video::-webkit-media-controls-timeline {
        display: none;
    }

    video::-webkit-media-controls-play-button {
        display: none;
    }
</style>
</head>
<body>

<style type="text/css">

    video{
        height:475px;
    }

    #clock {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: lightgrey;
        margin: auto;
    }

    span {
        display: block;
        width: 100%;
        margin: auto;
        padding-top: 3px;
        text-align: center;
        font-size: 35px;
    }

    @media only screen and (max-width: 768px) {
        video{
            height:350px;
        }
    }

    @media only screen and (max-width: 800px) {
        video{
            height:300px;
        }
    }

    @media only screen and (max-width: 480px) {
        video{
            height:200px;
        }
    }

    
</style>

<div class="container">

<h2>Wideo <?php echo $next_page_btn_a_or_b; ?></h2>
    <video controls autoplay="autoplay" id="myVideo">
      <source src="video/<?php echo $video; ?>" type="video/mp4">
    Your browser does not support the video tag.
    </video>
        <div style="text-align: center; margin: 25px;" >
        <div id="clock">
            <span id="seconds"></span>
        </div>
        <div id="next-page-btn" style="display:none;">
            <a href="<?php echo $next_page_url; ?>" class="btn btn-success btn-lg"><?php echo $next_page_btn_txt; ?></a>
        </div>
    </div>
</div>


<p>. <?php echo $copyright_url; ?> </p>


<script type="text/javascript">
//timeLeft = <?php echo $timer; ?>;
//timeLeft = 2;
console.log(document.getElementById("myVideo"));
timeLeft = document.getElementById("myVideo").duration;
console.log(timeLeft);

function countdown() {
    timeLeft--;
    document.getElementById("seconds").innerHTML = String( timeLeft );
    if (timeLeft > 0) {
        setTimeout(countdown, 1000);
    }else{
        document.getElementById("clock").style.display = "none";
        document.getElementById("next-page-btn").style.display = "block";
    }
};

// setTimeout(countdown, 1000);

var video = document.getElementById('myVideo');
video.addEventListener('loadeddata', function() {
   // Video is loaded and can be played
   console.log("video loaded");
   timeLeft = Math.round(document.getElementById("myVideo").duration);
   console.log(timeLeft);
   setTimeout(countdown, 1000);
}, false);

</script>
</body>
</html>
