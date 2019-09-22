<?php
session_start();
  
$user_admin = $_SESSION['user'];
$notification_id = $_GET['notification']; 
$admin_id = $user_admin['user_id']; 
  
      

if(!isset($_SESSION['user'])){ 
    $_SESSION['url'] = $_SERVER[REQUEST_URI];
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en" style="background:#FFF;">

<head>
    <?php require_once('views/header.inc.php') ?>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php require_once("views/menu.inc.php"); ?>
            <!-- /.navbar-static-side -->
        </nav>
        
        <div id="page-wrapper">
           <?php require_once("views/body.inc.php"); ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php require_once('views/footer.inc.php'); ?>

</body>

</html>
