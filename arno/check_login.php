<?php
session_start();
require_once('models/UserModel.php');
if(isset($_POST['username']) && isset($_POST['password'])){

    $user = $_POST['username'];
    $pass = $_POST['password'];
    $model = new UserModel;
    $user = $model->getLogin($user,$pass);

    
	
    if(count($user) > 0){
		
        $_SESSION['user'] = $user;

        echo "<script language=\"JavaScript\" type=\"text/javascript\"> window.parent.refresh();</script>";
    }else{
        echo "<script language=\"JavaScript\" type=\"text/javascript\"> window.parent.error();</script>";
    }

}else{
    header("Location index.php");
}


?>