<?php

function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
    }else{
      header('Location: ' . $url);
      die();
    }    
}

function isClean($data){
	if(preg_match('/^[a-z0-9 .\-]+$/i', $data)) {
		return true;
	} else return false;
}

function getRequest($data) {
	return substr($data, 17);
}

function hashPassword($password) {
	return $password ^ 2;
}

function dehasPassword($password) {
}
// /eyespy/panel.php
?>