<?php
session_save_path("/hermes/bosoraweb093/b1118/ipg.argentetpierrescom/");
session_start();
?>
<center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>The HQ Corp.</title>
</head>
<?php
// ensure session is inactive, otherwise kill it and refresh page manually (avoids any kind of XSS scripting stuff..)
if($_SESSION['isActive'] == true) {
	$_SESSION['isActive'] = false;
	die("You previous session is still active. Killing it now so you may refresh the page and login.");
	// kill session
}

?>
<body>
    <centre>
<h1>Authentication required
</h1>
<form id="form1" name="form1" method="post" action="panel.php">
  <p>Password:<br />
    <input type="text" name="pw" id="pw" />
    <br />
    <input type="submit" name="Submit" id="Submit" value="Submit" />
  </p>
</form>
<p>&nbsp;</p>
<?php // if the error session variable is set, something went wrong and the user needs to be informed of what. 
if(isset($_SESSION['error'])) 
	echo "<b>" . $_SESSION['error'] . "</b>";
?>
    </centre>
</body>
</html>
</center>