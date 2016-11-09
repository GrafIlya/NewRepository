<?php 
	
	mb_internal_encoding("UTF-8");
	$login = $_POST['_login'];
	$password = $_POST['_password'];
	
	$TryLogin = "admin";
	$TryPassword = "hXt-9DoAQB";
		
	if($login == $TryLogin && $password == $TryPassword)
	{
		print "Good boy";
	}
?>