<?php //functions

function sendmail($username, $subject, $emailadd, $mbody, $altbody) {

	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('Etc/UTC');

	// require '../PHPMailer/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();

	//Tell PHPMailer to use SMTP
	$mail -> isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail -> SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail -> Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail -> Host = 'smtp.gmail.com';

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail -> Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail -> SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$mail -> SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail -> Username = "useraccount@nalosolutions.com";

	//Password to use for SMTP authentication
	$mail -> Password = "nalosol123";

	//Set who the message is to be sent from
	$mail -> setFrom('useraccount@nalosolutions.com', 'NALO Solutions Limited');

	//Set an alternative reply-to address
	$mail -> addReplyTo('useraccount@nalosolutions.com', 'NALO Solutions Limited');

	//Set who the message is to be sent to
	$mail -> addAddress($emailadd, $username);

	//Set the subject line
	$mail -> Subject = $subject;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

	$mail -> Body = $mbody;

	//Replace the plain text body with one created manually
	$mail -> AltBody = $altbody;

	//Attach an image file
	// $mail->addAttachment('logo_teksol.png');

	//send the message, check for errors
	if (!$mail -> send()) {
		"Mailer Error: " . $mail -> ErrorInfo;
	} else {
		"Message sent!";
	}

}

function checkCode($code) {
	if ($code == '246') {
		$msisdnLeng = 6;
		return $msisdnLeng;
	} elseif ($code == '238' || $code == '241' || $code == '239' || $code == '220') {
		$msisdnLeng = 7;
		return $msisdnLeng;
	} elseif ($code == '229' || $code == '257' || $code == '237' || $code == '236' || $code == '235' || $code == '225' || $code == '243' || $code == '231' || $code == '223' || $code == '227' || $code == '228') {
		$msisdnLeng = 8;
		return $msisdnLeng;
	} elseif ($code == '242' || $code == '44' || $code == '240' || $code == '254' || $code == '265' || $code == '250' || $code == '221' || $code == '232' || $code == '211' || $code == '255' || $code == '256' || $code == '260' || $code == '233') {
		$msisdnLeng = 9;
		return $msisdnLeng;
	} elseif ($code == '1' || $code == '1' || $code == '234' || $code == '218' || $code == '263') {
		$msisdnLeng = 10;
		return $msisdnLeng;
	} else {
		$msisdnLeng = 10;
		return $msisdnLeng;
	}

}

function dateDifference($date_1, $date_2, $differenceFormat = '%a') {
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);

	$interval = date_diff($datetime1, $datetime2);

	return $interval -> format($differenceFormat);

}

function msisdn_prep($number) {

	$firstletter = $number[0];
	$secondletter = $number[1];
	$numcode = $firstletter . $secondletter;
	if ($firstletter == '+') {
		$number = substr($number, 1);
	} elseif ($numcode == 00) {
		$number = substr($number, 2);
	} elseif ($firstletter == 0 && $secondletter !== 0) {
		$remove = substr($number, 1);
		$number = '233' . $remove;
	}

	return $number;

}

function no_pages($no_chars) {

	if ($no_chars <= 160) {
		$no_pages = 1;
	}
	if ($no_chars > 160 && $no_chars <= 306) {
		$no_pages = 2;
	}
	if ($no_chars > 306 && $no_chars <= 459) {
		$no_pages = 3;
	}
	if ($no_chars > 459 && $no_chars <= 621) {
		$no_pages = 4;
	}
	if ($no_chars > 621) {
		$no_pages = 5;
	}

	return $no_pages;
}

function show_alert($type, $msg) {

	return "<div class='alert alert-" . $type . "'>
					  <button type='button' class='close' data-dismiss='alert'>&times;</button>
					  " . $msg . "
					</div>";
}

function which_network($msisdn) {
	$no = substr($msisdn, 3, 2);

	if ($no == '24' || $no == '54') {
		$net = 'MTN';
	} elseif ($no == '20' || $no == '50') {
		$net = 'VODAFONE';
	} elseif ($no == '27' || $no == '57') {
		$net = 'TIGO';
	} elseif ($no == '26') {
		$net = 'AIRTEL';
	} elseif ($no == '23') {
		$net = 'GLO';
	} elseif ($no == '28') {
		$net = 'EXPRESSO';
	} else {
		$net = 'UNKNOWN';
	}

	return $net;
}

function sendsms($id, $job_id, $user_sess, $msisdn, $sender,$sms_count,$network,$msgs,$submitdt,$created_by) {
	$con = mysqli_connect("localhost", "root", "", "dlr");
	$count = 0;

	$msgs = urldecode($msgs);
	$msgs = urlencode($msgs);

	// remove carriage returns
	// ==========RUN DIRECT URL TOO===========

	$msgs = str_replace("%5Cr", '%20', $msgs);
	// remove new lines
	$msgs = str_replace("%5Cn", '%20', $msgs);
	// remove carriage returns

	$msg = urldecode($msgs);

	$sql = "INSERT INTO logs(id, job_id, username, msisdn, sender, sms_count, network, message,submit_date, status,created_by)
					VALUES ('$id', '$job_id', '$user_sess', '$msisdn', '$sender','$sms_count','$network','$msg','$submitdt','ACK','$created_by')";
	mysqli_query($con, $sql);

	$dlr = "http://localhost/nalosms/sendsmsdlr.php?source=%P&destination=%p&SVC=%n&msgID=%F&msg=%a&msglen=%L&timestamp=%t&status=%d&smsc=%i&smsid=%I&dlrv=%d&MDS=%D&DLRS=%A&osms=%b&mid=" . $id;
	$dlrEncode = urlencode($dlr);

	echo $url = "http://168.144.175.52:13013/cgi-bin/sendsms?username=guiuser&password=O14ns0&to=" . $msisdn . "&from=" . $sender . "&text=" . $msgs . "&dlr-mask=31&dlr-url=" . $dlrEncode;
	echo $urloutput = file_get_contents($url);

}

function sendsomesms($destination, $sender, $msgs, $counter, $user_sess, $sms_count) {
	$con = mysqli_connect("localhost", "root", "", "dlr");
	//connection
	// $con=mysqli_connect("localhost","root","","dlr"); //connection
	$count = 0;
	while ($count < $counter) {
		$number = $destination[$count];
		$msisdn = preg_replace('/\D/', '', $number);

		$msisdn = msisdn_prep($msisdn);
		//put msisdn in proper format

		$network = which_network($msisdn);

		$id = uniqid('NS', true);

		return $sql = "INSERT INTO logs(id, username, msisdn, sender, sms_count, network, message, status) VALUES ('$id', '$user_sess', '$msisdn', '$sender','$sms_count','$network','$msgs','ack')";

		mysqli_query($con, $sql);

		// $msgs = urlencode($msgs);

		// $url = "http://168.144.175.52:13013/cgi-bin/sendsms?username=tester&password=foobar&to=" . $msisdn . "&from=" . $sender . "&text=" . $msgs . "&smsc=buzismsc1&dlr-mask=31";

		// $url = "http://5.231.54.137:13013/cgi-bin/sendsms?username=tester&password=foobar&to=" . $msisdn . "&from=" . $sender . "&text=" . $msgs . "&smsc=buzismsc1&dlr-mask=31";
		// $urloutput = file_get_contents($url);

		$count++;
	}

	$rem_start_point = $count;

	$rem_array = array_slice($destination, $rem_start_point);

	$no_rem = count($rem_array);

	$msisdn_string = implode($rem_array, ',');

	$sql2 = "INSERT INTO q_msgs (username, msisdn, sender, msg, sms_count, status ) 
				VALUES ('$user_sess', '$msisdn_string', '$sender', '$msgs', '$sms_count','pending' )";

	if (mysqli_query($con, $sql2)) {

		// return $no_rem;
	}

}

function fetchReport($filename, $tid, $user_sess) {

	$run = mysqli_query($link, $query);
	if (file_exists($filename)) {
		$file = fopen($filename, "r");
		echo "<table id='" . $tid . "' class='display' cellspacing='0' width='100%' style='font-size:13px;'>
				<thead>
				<tr align='center' cellpadding='30px'>
					<td>DATE</td>
					<td>MESSAGE ID</td>
					<td>SENDER</td>
					<td>DESTINATION</td>
					<td>USER</td>
					<td>STATUS</td>
				</tr>
				</thead>
				<tbody>";
		while (!feof($file)) {
			$fetched = fgets($file);
			$ripped = explode('|', $fetched);
			if (isset($ripped[5])) {
				if ($ripped[5] == 2) {
					$status = 'DELIVRD';
				} elseif ($ripped[5] == 1) {
					$status = 'ENROUTE';
				} elseif ($ripped[5] == 3) {
					$status = 'EXPIRED';
				} elseif ($ripped[5] == 4) {
					$status = 'DELETED';
				} elseif ($ripped[5] == 5) {
					$status = 'UNDELIVERABLE';
				} elseif ($ripped[5] == 6) {
					$status = 'ACCEPTED';
				} elseif ($ripped[5] == 7) {
					$status = 'UNKNOWN';
				} elseif ($ripped[5] == 8) {
					$status = 'REJECTED';
				}

				$usernames = explode(",", $user_sess);
				foreach ($usernames as $key) {

					if ($ripped[4] == $key) {
						echo "<tr align='center' cellpadding='30px'>
						<td>" . $ripped[0] . "</td>
						<td>" . $ripped[1] . "</td>
						<td>" . $ripped[2] . "</td>
						<td>" . $ripped[3] . "</td>
						<td>" . $ripped[4] . "</td>
						<td>" . $status . "</td>
					</tr>";
					}
				}
			}
		}
		echo "</tbody></table>";
		fclose($file);
	} else {
		echo "<p>No records for given day</p>";
	}
}
?>