<?php
	session_start();
	
	require_once 'class/class.user.php';
	$session = new USER();
	
	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin())
	{
		// session no set redirects to login page
		$session->redirect('login.php');
	} else {
		$userRow = $session->dologin($_SESSION['user_session'],$_SESSION['user_mail'],$_SESSION['user_pass']);
		$user_id = $_SESSION['user_session'];
		$user_email = $_SESSION['user_mail'];
		$user_name = $_SESSION['user_name'];
	}
	
	require_once 'class/class.account.php';
	$session = new ACCOUNT();
	
	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin_c())
	{
		// session no set redirects to login page
		$session->redirect('login_c.php');
	} else {

	}
	
	require_once 'class/class.lancamentos.php';
	$session = new MOVS();
	
	
?>