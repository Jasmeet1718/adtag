<?php
include "includes/connection.php";
error_reporting(E_ERROR | E_PARSE);

session_start();
if(!$_SESSION["username"]){
  header("location: index.php");
}
date_default_timezone_set('Asia/Kolkata');
$time = date('Y-m-d H:i:s');
if(isset($_POST['submit'])){
    $adtag_type=$_POST['adtag_type'];
    $geo=$_POST['geo'];
    $campaign_name=$_POST['campaign_name'];
    $client=$_POST['client'];
    $fcat=$_POST['fcat'];
    $publisher=$_POST['publisher'];
    $dims=$_POST['dims'];
    $master_client=$_POST['master_client'];
    $dev_name=$_SESSION["username"];
    $sql="INSERT INTO `adtagdata`(`create_time`,`developer_name`,`adtag_type`, `geo`, `campaign_name`, `master_client` ,`client`, `fcat`, `publisher`, `dims`, `status`,`status_v`) VALUES ('$time','$dev_name','$adtag_type','$geo','$campaign_name', '$master_client' ,'$client','$fcat','$publisher','$dims','staging','staging')";

    $result=mysqli_query($connectDB,$sql);
    if($result){
        header("location: console.php");
    }
    // else{
    //     // echo "Failed: ".mysqli_error($conn);
    // }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        #box {
            padding: 5px;
            border: 3px solid black;
            border-radius: 5px;
            cursor: pointer;
            overflow: hidden;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div id="box">
        <form action="" method="post" >
            <!-- <label for="Adtag Type :"><input type="text" name="Name" id=""></label> <br> -->
            Adtag Type: <select name="adtag_type">
                <option id="typ1" value="DCM">DCM</option>
                <option id="typ3" value="Dv360" >Dv360</option>
                <option id="typ2" value="Dv360Dbmc">Dv360Dbmc</option>
                <option value="DFP">DFP</option>
                <option value="CRITEO">CRITEO</option>
                <option value="Sports">Sports</option>
            </select><br><br>
            <input type="radio" id="geocity" name="geo" value="true">
            <label for="city">Geo City / Weather</label><br><br>
            <input type="radio" id="geobcammp" name="geo" value="bcamp">
            <label for="bcamp">Geo Bcamp (used to provide city data and store it) / Weather</label><br><br>
            <input type="radio" id="fcat_nan" name="geo" value="fcat_nan" onclick="disable()">
            <label for="fcat_nan">Fcat not required</label><br><br><br>
            Campaign Name:&nbsp; <input type="text" name="campaign_name" id="" required> <br><br>
            Master-client: 
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="text" name="master_client" id="" required> <br><br>
           Client: 
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="text" name="client" id="" required> <br><br>
           <span id="fcat">Fcat:
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="fcat" id="block"> <br><br></span>  
            Publisher:
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="publisher" id=""> <br><br>
            Custom Dimension(s):[WidthxHeight format, separated with comma] 
            &nbsp;
            <input type="text" name="dims" id="" required><br><br>
            <button type="submit" name="submit">Submit</button> <button onclick="location='./'">Back</button><br><br>
        </form> <br><br>
        
    </div>
    
    <script>
        function disable(){
            if(document.getElementById('fcat_nan').checked) {
             document.getElementById('fcat').style.display="none";
             document.getElementById("typ1").disabled=true;
             document.getElementById("typ2").disabled=true;
             document.getElementById("typ3").selected=true;
            }
            else{
            document.getElementById('fcat').style.display="block";
            }
        }
    </script>
</body>

</html>