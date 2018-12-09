<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oah";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if(isset($_POST['minisubmit'])){
	
	$email = $_POST['email'];
  	$subject = $_POST['subject'];
  	$date = $_POST['datepicker'];
	$page_count = $_POST['pageNo'];
	
   $query = " SELECT id FROM `mini_form_data` ORDER BY timestamp DESC ";
   $result = $conn->query($query);
   if(!$result){
	   echo 'Could not run query'.mysql_error();
	   exit;
   }
   
   if ($result->num_rows > 0) {
	   $pre_user_id = $row[0];
      $num_pre_user_id = substr($pre_user_id,15); // It will get the numeric part of the user_id
      $id = (int)$num_pre_user_id + 1;
      $id = 'EXP02071993AERO'.$id;   
   }
   else {
	   $id = 'TMP02071993AERO1'; 
   }
   
   $sql = "INSERT INTO `mini_form_data`(`id`,`email`,`subject`,`date`,`page_count`) VALUES ('$id','$email','$subject','$date','$page_count');";
   
	if ($conn->query($sql) === TRUE) {
		header('Location: login.php');
	} 
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

if(isset($_POST['callsubmit'])){
	
	$phone = $_POST['call_phone'];
	$type = $_POST['call_type'];
	$reason = $_POST['call_reason'];
	$time = $_POST['call_time'];
	
   $sql = "INSERT INTO `callback_form_data` (`id`,`phone`,`order_type`,`reason`,`contact_time`) VALUES ('104','$phone','$type','$reason','$time');";

	if ($conn->query($sql) === TRUE) {
		$message = "We will call you later ! ";
      echo "<script type='text/javascript'>alert('$message');</script>";
	   echo "<script>setTimeout(\"location.href = 'index.php';\",50);</script>";   
	} 
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

if(isset($_POST['registersubmit'])){
	
	$name = $_POST['regname'];
	$email = $_POST['regemail'];
	$country = $_POST['regcountry'];
	$specialization = $_POST['regspecialization'];
	$dob = $_POST['regdob'];
	$userid = $_POST['reguserid'];
	$password = $_POST['regpassword'];
	
   $sql = "INSERT INTO `register_form_data` (`name`,`email`,`country`,`specialization`,`dob`,`userid`,`password`) 
                     VALUES ('$name','$email','$country','$specialization','$dob','$userid','$password');";

	if ($conn->query($sql) === TRUE) {
		header("location:login.php");    
	} 
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

if(isset($_POST['signsubmit'])){
	
	 $userid    = $_POST['signuserid'];
    $password = $_POST['signpassword'];
   
    $sql = "SELECT userid, password FROM `register_form_data` WHERE userid='$userid' AND password = '$password';";
	 $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    // output data of each row
     header("location:welcome.php");    
   }
    else{
		echo "Invalid Details";
	} 
}

//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if (isset($_REQUEST['query'])) {
	 $req    = $_POST['query'];
	 $request = mysql_real_escape_string( $req );
	 $sql = "SELECT * FROM `subject_list` WHERE subject LIKE '%".$request."%'";
	 $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
    // output data of each row
       while($row = mysqli_fetch_assoc($result))
         {
					$data[] = $row["subject"];
			}
       echo json_encode($data); 
   }
}

$conn->close();
?>

