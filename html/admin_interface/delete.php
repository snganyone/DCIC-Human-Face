<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

  <title>DCIC Human Face of Big Data</title>

<?php
//Connection to PostgreSQL
require '../credentials.inc.php';

//ini_set('display_errors', 1);

$connect = pg_connect('host=' . DBHOST . ' dbname=' . DBNAME . ' user=' . DBUSER . ' password=' . DBPASS);
if (!$connect){
  die("Error in connection!:" . pg_last_error());
}
else{
  //echo "Successfully connected to database:" . " " .pg_dbname() . " on " . pg_host();
}

//Processed passed pid variable from parcels.php page
/*If a user selects a row from the parcels table to delete
this condition will run
*/
$p = $_GET["pid"];

if($p){
  $i = "SELECT * FROM humanface.parcels WHERE parcel_id = " . $p;
  $ip = pg_query($connect, $i);
  $ia = pg_fetch_assoc($ip);
}
//Query Address Information
$aquery = "SELECT st_num, st_name
          From humanface.addresses
          WHERE parcel_id = " . $p ."
          ORDER BY parcel_id";
$a = pg_query($connect, $aquery);

//Query Event information
$event = "SELECT * FROM humanface.events e
          JOIN event_types et on e.type = et.id
          WHERE e.parcel_id =" . $p;
$e = pg_query($connect, $event);

//Query Event people
$people = "SELECT e.event_id, ep.event_id, p.person_id, ep.role, p.name
            FROM events e
          	JOIN event_people_assoc ep ON e.event_id = ep.event_id
          	JOIN people p ON ep.person_id = p.person_id";
$ep = pg_query($connect, $people);

?>

</head>
<body>
  <!-- DCIC Logo -->
  <div class="section-header text-center">
  <img src="../images/LOGO.png" alt="DCIC Logo">
  </div>

  <div class="alert alert-danger text-center" role="alert">
  Selecting the Delete button will permanently remove the record from the system.
  </div>

  <!-- PHP Form -->

  <form method="post" action="data.php" name="form" id="form" style="margin: 0 auto; width: 80%;">
  <input id="parcel_id" type="hidden" name="parcel_id" value="<?=$ia['parcel_id']?>">
    <h2 class="text-center">Parcel Information</h2>

    <div style="border: 1px solid; border-radius: 5px;">
    <div class="form-row">
    <div class="form-group col-sm-2">
    <label class="float-md-center" for="Parcel ID">Parcel ID</label>
    <input class="form-control" type="text" id="parcel_id" name="parcel_id" value="<?=$ia['parcel_id']?>">
    </div>
    <div class="form-group col-sm-2">
    <label class="float-md-center" for="block_number">Block Number</label>
    <input class="form-control" type="text" id="block_number" name="block_number" value="<?=$ia['block_no']?>">
    </div>
    <div class="form-group col-sm-2">
    <label class="float-md-center" for="parcel_number">Parcel Number</label>
    <input class="form-control" type="text" id="parcel_number" name="parcel_number" value="<?=$ia['parcel_no']?>">
    </div>
    <div class="form-group col-sm-2">
    <label class="float-md-center" for="ward_number">Ward Number</label>
    <input class="form-control" type="text" id="ward_number" name="ward_number" value="<?=$ia['ward_no']?>">
    </div>
    <div class="form-group col-sm-2">
    <label class="float-md-center" for="land_use">Land Use</label>
    <input class="form-control" type="text" id="land_use" name="land_use" value="<?=$ia['land_use']?>">
    </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Street Number</label>
        <input class="form-control" type="text" id="st_num" name="st_num">
      </div>
      <div class="form-group col-md-6">
        <label>Street Name</label>
        <input class="form-control" type="text" id="st_num" name="st_num">
      </div>
    </div>
  </div>
<br><br><br>
  <h2 class="text-center">Event Information</h2>

  <div style="border: 1px solid; border-radius: 5px;">
  <div class="form-row">
    <div class="form-group col-sm-4">
      <label>Type</label>
      <input class="form-control" type="text" id="type" name="type">
    </div>
    <div class="form-group col-sm-4">
      <label>Date</label>
      <input class="form-control" type="text" id="date" name="date">
    </div>
    <div class="form-group col-sm-4">
      <label>Price</label>
      <input class="form-control" type="text" id="price" name="price">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-sm-6">
      <label>Response</label>
      <input class="form-control" type="text" id="response" name="response">
    </div>
    <div class="form-group col-sm-6">
      <label>Extra Information</label>
      <input class="form-control" type="text" id="extra_info" name="extra_info">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-sm-6">
    <label>Role</label>
    <input class="form-control" type="text" id="role" name="role">
    </div>
    <div class="form-group col-sm-6">
    <label>Name</label>
    <input class="form-control" type="text" id="name" name="name">
    </div>

    <div class="form-group col-sm-6">
    <label>Role</label>
    <input class="form-control" type="text" id="role1" name="role1">
    </div>
    <div class="form-group col-sm-6">
    <label>Name</label>
    <input class="form-control" type="text" id="name1" name="name1">
    </div>
  </div>
  </div>

  <?php echo $event; ?>
<br><br><br>
    <button type="submit" class="btn btn-danger" name="delete" id="delete">Delete</button>
    </form>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

<script>

//JavaScript Code

function validateForm(){
  //Form Validation
  var x, y, z, v, w;
  x = document.forms["form"]["parcel_id"].value;
  y = document.forms["form"]["block_no"].value;
  z = document.forms["form"]["parcel_no"].value;
  w = document.forms["form"]["ward_no"].value;
  v = document.forms["form"]["land_use"].value;

  //Validates that all Form fields are completed
  if(x == "" || y == "" || z == "" || w == "" || z == "" || v == ""){
    alert("Please fill out all form fields");
    return false;
  }

  //Validates that the block, parcel, and ward numbers are integers
  //Parcel ID
  if(isNaN(x)){
    alert("Please enter a number in the Parcel ID field");
    return false;
  }
  //Block Number
  if(isNaN(y)){
    alert("Please enter a number in the Block Number field");
    return false;
  }
  //Parcel Number
  if(isNaN(z)){
    alert("Please enter a number in the Parcel Number field");
    return false;
  }
  //Ward Number
  if(isNaN(w)){
   alert("Please enter a number in the Ward Number field");
   return false;
  }
}

//JQuery Code

$("#parcel_id").on("click", function(){
  alert("Please provide the Parcel ID number!");
});

//Form Submission Event
</script>
</body>
</html>
