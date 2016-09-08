<?php
ob_start();
// $con=mysqli_connect("168.144.85.195","nalo","msyWE546","mobile");
// $con=mysqli_connect("168.144.175.52","nalo","msyWE546","mobile"); 
// $con=mysqli_connect("78.46.254.50","nalo","nsd8dmd3bv0s","mobile"); 
// $con=mysqli_connect("localhost","root","","dlr");
$con = mysqli_connect('136.243.72.73', 'nalo', 'AvH89oU1N', 'zoom');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>  