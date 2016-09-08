<?php
$cred_query = mysqli_query($con, "SELECT new_bal, currency, price FROM credit WHERE username = '$user_sess'");

$credit_array = mysqli_fetch_array($cred_query);

$bal = $credit_array['new_bal'];
$curr = $credit_array['currency'];
$price = $credit_array['price'];

$rem_sms = floor($bal/$price); //remaining SMS rouded to a whole number

if($rem_sms<51){
	$red = "class=text-danger";
}
?>