<?php
session_start();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('config.xlsx');
 
$sheet = $spreadsheet->getActiveSheet();

$sheetRow = $sheet->getHighestRow();

$submitText = "Prześlij i przejdź do następnej pary";

if(!isset($_SESSION['vote_pairs'])){
   $_SESSION['vote_pairs'] = array();
}else{
    if(count($_SESSION['vote_pairs']) >= ($sheetRow-1) ){
        header('Location: thankyou.php');
    }

}

$pairNumberArray = array();
for($i=2;$i<=$sheetRow;$i++){
    // array_push($pairNumberArray,$sheet->getCell('A'.$i)->getValue());
    array_push($pairNumberArray,$i);
}


if((count($pairNumberArray) - count($_SESSION['vote_pairs'])) == 1){
        $submitText = "Prześlij i Zakończ";
}

// echo "<pre>";
// print_r($pairNumberArray);
// print_r($_SESSION['vote_pairs']);
//die();



//echo $sheetRow;
$video = '';
$next_page_btn_txt = '';
$next_page_url = '';
$pair_id = $_GET['pair_id'];
$number = $_GET['number'];
for($i=2;$i<=$sheetRow;$i++){
    if($pair_id == $i){
        //$pair_id = $sheet->getCell('A'.$i)->getValue();
          $video_a = $sheet->getCell('B'.$i)->getValue();
          $video_a_desc = $sheet->getCell('D'.$i)->getValue();
          $video_b = $sheet->getCell('B'.$i)->getValue();
          $video_b_desc = $sheet->getCell('E'.$i)->getValue();

          //$next_page_url = 'video.php?pair_id='.($pair_id+1).'&video=first';
          //$random_pair = rand(2,$sheetRow);
          //if(in_array($random_pair,$_SESSION['vote_pairs']))
          //$next_page_url = 'video.php?pair_id='.(rand(2,$sheetRow)).'&video=first';
        break;
    }
}


// save data

 //$_SESSION['id'] = null;
if(!isset($_SESSION['id'])){
   $_SESSION['id'] = md5(uniqid(rand(), true));
}

// print_r($_SESSION);
// die();

$error = '';

if(isset($_POST["vote"]))
{

    array_push($_SESSION['vote_pairs'],$_POST["pair_id"]);

    // print_r($_SESSION);
    // die();

   if(isset($_SESSION['vote'])){
      $error = '<label class="text-success">Już głosowałeś. Dziękujemy za głosowanie.</label>';
   }else{
      if($error == '')
        {
         $file_open = fopen("vote_data.csv", "a");
         $no_rows = count(file("vote_data.csv"));
         if($no_rows > 1)
         {
          $no_rows = ($no_rows - 1) + 1;
         }

         if($_POST["video"] == 'Video A'){
            $file = $sheet->getCell('B'.($_POST["pair_id"]))->getValue();
         }else{
            $file = $sheet->getCell('C'.($_POST["pair_id"]))->getValue();
         }

         if($number == 1){
            if($_POST['video'] == 'Video A'){
                $videoData = 'Video A';
            }else{
                $videoData = 'Video B';
            }
            //$videoData = $_POST['video'];
         }else{
            if($_POST['video'] == 'Video A'){
                $videoData = 'Video B';
            }else{
                $videoData = 'Video A';
            }
         }

         $form_data = array(
          'sr_no'  => $no_rows,
          'Session Id' => $_SESSION['id'],
          'Selection' => $videoData,
          'file' => $file,
          'pair_id' => $_POST["pair_id"]-1,
          'timestamp' => gmdate("l jS \of F Y h:i:s A").' UTC',
         );
         fputcsv($file_open, $form_data);
         $error = '<label class="text-success">Dziękujemy za głosowanie nas</label>';
         
         if($_POST["pair_id"] == $sheetRow){
          //$_SESSION['vote'] = "yes";
         }

         //$output = array_merge(array_diff($pairNumberArray, $_SESSION['vote_pairs']), array_diff($_SESSION['vote_pairs'], $pairNumberArray));
         $output = array_diff($pairNumberArray, $_SESSION['vote_pairs']);
        shuffle($output);
        //print_r($output);
        
        if(count($output) > 0){
            $new_pair_id = array_rand($output,1);
            //echo $new_pair_id;
            if($new_pair_id || $new_pair_id == 0){
                $next_page_url = 'video.php?pair_id='.$output[$new_pair_id].'&video=first';
             }else{
                $next_page_url = 'thankyou.php';
             }


             if(count($output) == 1){
                $submitText = "Prześlij i zakończ";
             }
        }else{
            $next_page_url = 'thankyou.php';
        }
           
         //echo $next_page_url;
         //die();
         header('Location: '.$next_page_url);
        }
   }
     
}


// getting pair data


?>
<!DOCTYPE html>
<html>
 <head>
  <title>Głosowania Strona</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
    
   <h2 align="center">To jest sekcja dotycząca głosowania na streszczenie wideo</h2>
   <br />
   <!-- <h1 align="center"><?php echo $video_a_desc; ?></h1>
   <br />
   <h1 align="center"><?php echo $video_b_desc; ?></h1> -->

   <div class="col-md-6" style="margin:0 auto; float:none;">
    <form method="post" action="vote.php?pair_id=<?php echo $pair_id; ?>&number=<?php echo $number; ?>">
     <h3 align="center">Który film najlepiej opisuje streszczenie?<br> Proszę zagłosuj!!</h3>
     <br />
     <?php echo $error; ?>
    
     <input type="hidden" name="pair_id" value="<?php echo $pair_id; ?>">
    <div style="display: flex; justify-content: space-between;">

      <?php if($number==1){ ?>
         <div class="form-group">
           <input class="from-control" type="radio" name="video" value="Video A" required="true" id="flexRadioDefault1" style="font-size: 50px;height: 30px;width: 30px;">
           <label class="form-check-label" for="flexRadioDefault1" style="font-size: 50px; font-weight: 400;">
            Wideo A
           </label>
         </div>
         <div class="form-group">
           <input class="from-control" type="radio" name="video" value="Video B" required="true" id="flexRadioDefault2" style="font-size: 50px;height: 30px;width: 30px;">
           <label class="form-check-label" for="flexRadioDefault2" style="font-size: 50px; font-weight: 400;">
            Wideo B
           </label>
         </div>
      <?php }else{ ?>
         <div class="form-group">
           <input class="from-control" type="radio" name="video" value="Video B" required="true" id="flexRadioDefault1" style="font-size: 50px;height: 30px;width: 30px;">
           <label class="form-check-label" for="flexRadioDefault1" style="font-size: 50px; font-weight: 400;">
            Wideo A
           </label>
         </div>
         <div class="form-group">
           <input class="from-control" type="radio" name="video" value="Video A" required="true" id="flexRadioDefault2" style="font-size: 50px;height: 30px;width: 30px;">
           <label class="form-check-label" for="flexRadioDefault2" style="font-size: 50px; font-weight: 400;">
            Wideo B
           </label>
         </div>
      <?php } ?>

        
     
    </div>

     <div class="form-group">
      <div style="text-align: center; margin: 25px;">
        <input type="submit" name="vote" class="btn btn-success btn-lg" value="<?php echo $submitText; ?>" />
    
    </form>
   </div>
  </div>
 </body>
</html>
