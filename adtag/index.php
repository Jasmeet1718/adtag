<?php
include "includes/connection.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!$_SESSION["username"]){
  header("location: login.php");
}
if($_SESSION["role"] == '2'){
  header("location: client.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Client Campaign Manager</title>
  <?php include "includes/head.php"; ?>
</head>
<body>
  <div class="maincontainer">
    <div class="header">
      <h1>Client Campaign Manager</h1>
    </div>

    

    <div class="inputs">
      <?php 
        if($_SESSION["role"] == 1) {
      ?>
      <form id="form">
      <?php
        } else if($_SESSION["role"] == 0) {
      ?>
      <form id="form2">
      <?php
        } else if($_SESSION["role"] == 3){
      ?>
      <form id="form4">
      <?php
        }
      ?>
        <div class="input-box">
          <label for="datefrom">From</label>
          <input type="date" name="datefrom" id="datefrom">
        </div>

        <div class="input-box">
          <label for="dateto">To</label>
          <input type="date" name="dateto" id="dateto">
        </div>

        <div class="input-box">
          <label for="clients">Clients</label>
          <select id="clients" multiple="multiple">
          </select>
        </div>

        <div class="input-box">
          <label for="campaigns">Campaigns</label>
          <select id="campaigns" multiple="multiple">
          </select>
        </div>

        <div class="input-box">
          <!-- <button onClick="window.location.reload()" name="submit">Show adtags</button> -->
          <button name="submit">Show adtags</button>
        </div>

        <div class="btn-gp">
          <?php 
            if($_SESSION["role"] == 0){
          ?>
          <button><a href="console.php">console</a></button>
          <?php } ?>
          <button><a href="logout.php">logout</a></button>
        </div>
      </form>
    </div>

    <div id="table" class="table">
      <?php if($_SESSION["role"] == 3){?>
       <table>
  <thead>
  <th style="text-align: center;">Date</th>
  <th style="text-align: center;">Client</th>
  <th style="text-align: center;">Campaign</th>
  <th style="text-align: center;">Previews</th>
      <th style="text-align: center;">Adtags</th>
      <th style="text-align: center;">Status</th>
      <th style="text-align: center;">Remark</th>
    </thead>
    <?php
      $sql = "SELECT * FROM adtagdata WHERE status_v='staging'" ;
      $result = $connectDB->query($sql);
      
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          ?><tr>
      <td style="text-align: center;"><?php echo $row['date']?></td>
        <td style="text-align: center;"><?php echo $row['client']?></td>
        <td style="font-weight: bold;"><?php echo $row['campaign_name']?></td>
        <td style="text-align: center;">
        <a target="_blank" href="./previews.php?id=<?php echo $row['id']?>">url</a>
        </td>
      <td style="text-align: center;">
        <a target="_blank" href="./adtags.php?id=<?php echo $row['id']?>">url</a>
        </td>
        <td style="text-align: center;">
        <select name="status_v" id="status_v-<?php echo $row['id']?>">
        <option value="staging">staging</option>
        <option value="active">active</option>
        </select>
        </td>
        <td style="display:flex;text-align: center; justify-content: space-evenly;">
        <textarea id="<?php echo $row['id']?>"><?php echo $row['remark']?></textarea>
        <button onClick="updateRemark(<?php echo $row['id']?>)">Save</button>
        </td>
        </tr>
        <?php }}}
        else if ($_SESSION["role"] == 0){ ?>
          <table>
    <thead>
      <th style="text-align: center;">Date</th>
      <th style="text-align: center;">Client</th>
      <th style="text-align: center;">Campaign</th>
      <th style="text-align: center;">Previews</th>
      <th style="text-align: center;">Adtags</th>
      <th style="text-align: center;">Status</th>
      <th style="text-align: center;">Remark</th>
    </thead>
    <?php
      $sql = "SELECT * FROM adtagdata WHERE status_v='staging'" ;
      $result = $connectDB->query($sql);
      
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          ?><tr>
      <td style="text-align: center;"><?php echo $row['date']?></td>
      <td style="text-align: center;"><?php echo $row['client']?></td>
      <td style="font-weight: bold;"><?php echo $row['campaign_name']?></td>
      <td style="text-align: center;">
        <a target="_blank" href="./previews.php?id=<?php echo $row['id']?>">url</a>
      </td>
      <td style="text-align: center;">
        <a target="_blank" href="./adtags.php?id=<?php echo $row['id']?>">url</a>
      </td>
      <td style="text-align: center;"><?php echo $row['status']?></td>
      <td style="display:flex;text-align: center; justify-content: space-evenly;">
        <textarea id="<?php echo $row['id']?>"><?php echo $row['remark']?></textarea>
        <?php 
           if($row['status_v']=="active") {?>
            <button disabled>Save</button><?php } else { ?>
             <button onClick="updateRemark(<?php echo $row['id']?>)">Save</button>
       <?php }?> 
        
      </td>
    </tr>
       <?php }}} 
        else if ($_SESSION["role"] == 1){ ?> 
        <table>
        <thead>
          <th style="text-align: center;">Date</th>
          <th style="text-align: center;">Client</th>
          <th style="text-align: center;">Campaign</th>
          <th style="text-align: center;">Previews</th>
          <th style="text-align: center;">Adtags</th>
          <th style="text-align: center;">Status</th>
        </thead>
        <?php
      $sql = "SELECT * FROM adtagdata WHERE status_v='active'" ;
      $result = $connectDB->query($sql);
      
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          ?>
          <tr>
          <td style="text-align: center;"><?php echo $row['date']?></td>
          <td style="text-align: center;"><?php echo $row['client']?></td>
          <td style="font-weight: bold;"><?php echo $row['campaign_name']?></td>
          <td style="text-align: center;">
            <a target="_blank" href="./previews.php?id=<?php echo $row['date']?>">url</a>
          </td>
          <td style="text-align: center;">
            <a target="_blank" href="./adtags.php?id=<?php echo $row['date']?>">url</a>
          </td>
          <td style="text-align: center;">
            <input id="<?php echo $row['id']?>" class="stat-input" type="text" value="<?php echo $row['status']?>" />
            <button value="<?php echo $row['id']?>" class="stat-bttn" onclick="updateStatus(this.value)">Change</button>
          </td>
        </tr><?php }}} ?>
    </table></div>

  </div>
  <script type="text/javascript">
    let team = '<?php echo $_SESSION["team"] ?>'
  </script>
</body>
</html>