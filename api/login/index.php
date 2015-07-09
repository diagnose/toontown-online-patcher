<?php
$LAUNCHER_USER = $_GET["n"];
$LAUNCHER_PASS = $_GET["p"]; //[n, p] are preset by the launcher and I *can't* change that.
if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
	$IP = $_SERVER['HTTP_CF_CONNECTING_IP'];
}
else{
	$IP = $_SERVER['REMOTE_ADDR'];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$DB_HOST = '';
$DB_USER = '';
$DB_PASS = '';
$DB_DTBS = '';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$SQL_CONNECTION = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
mysqli_select_db($SQL_CONNECTION, $DB_DTBS);
$LAUNCHER_USER = stripslashes($LAUNCHER_USER);
$LAUNCHER_USER = mysqli_real_escape_string($SQL_CONNECTION, $LAUNCHER_USER);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$IS_CLOSED = True;
$IS_TEST = True;

$SQL_RESULT = mysqli_query($SQL_CONNECTION, "SELECT * FROM `users` WHERE `Username`='$USERNAME'") or die("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=There was a problem verifying the Account ID.\n");;
$row = mysqli_fetch_array($SQL_RESULT);

if(password_verify($row['Password'], $LAUNCHER_PASS) == 1){
	$row = mysqli_fetch_array($SQL_RESULT);
	$ACCID = strval($row['ID']);
	if($row['Banned'] == 1){
		$LAUNCHER_RESPONSE =("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=This account is on hold. Please try again later.\nGLOBAL_URL_SPAWN=http://unset.com/bans/$LAUNCHER_USER\n");
		mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Banned')");
		mysqli_close();
	}
	else if($IS_CLOSED == True){
		if($row['Ranking'] == 'Administrator' or $row['Ranking'] == 'Moderator' or $row['Ranking'] == 'Developer'){
			$LAUNCHER_RESPONSE =("LOGIN_ACTION=PLAY\nLOGIN_TOKEN=asdf\nGAME_USERNAME=$LAUNCHER_USER\nGAME_DISL_ID=$ACCID\nUSER_TOONTOWN_ACCESS=FULL\nGAME_CHAT_ELIGIBLE=1");
			mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Closed (Admin)')");
			mysqli_close();
		}
		else{
			$LAUNCHER_RESPONSE =("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=Closed for maintenance.\n");
			mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Closed')");
			mysqli_close();
		}		
	}
	else if($IS_TEST == True){
		if($row['TestAccess'] == 1){
			$LAUNCHER_RESPONSE =("LOGIN_ACTION=PLAY\nLOGIN_TOKEN=IDontKnowRightNow\nGAME_USERNAME=$LAUNCHER_USER\nGAME_DISL_ID=$ACCID\nUSER_TOONTOWN_ACCESS=FULL\nGAME_CHAT_ELIGIBLE=1");
			mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Test')");
			mysqli_close();
		}
		else{
			$LAUNCHER_RESPONSE =("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=Account isn't registered for Test Toontown.\n");
			mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Non-Test')");
			mysqli_close();
		}
	}
	else if($row['Verified'] == 0){
		$LAUNCHER_RESPONSE=("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=Account isn't verified. Please check your e-mail.\n");
		mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Unverified')");
		mysqli_close();
	}
	else{
		$LAUNCHER_RESPONSE =("LOGIN_ACTION=PLAY\nLOGIN_TOKEN=IDontKnowRightNow\nGAME_USERNAME=$LAUNCHER_USER\nGAME_DISL_ID=$ACCID\nUSER_TOONTOWN_ACCESS=FULL\nGAME_CHAT_ELIGIBLE=1");
		mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher')");
		mysqli_close();
	}
}
else{
	$LAUNCHER_RESPONSE =("LOGIN_ACTION=LOGIN\nLOGIN_ERROR=LOGIN_FAILED\nGLOBAL_DISPLAYTEXT=Incorrect Username and/or Password.\n");
	mysqli_query($SQL_CONNECTION, "INSERT INTO `login_attempts` (`IP`, `Username`, `Location`) VALUES('$IP', '$USERNAME', 'Toontown Launcher - Banned')");
	mysqli_close();
}
echo $LAUNCHER_RESPONSE;
exit();
