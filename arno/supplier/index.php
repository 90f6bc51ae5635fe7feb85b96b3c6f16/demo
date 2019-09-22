<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">

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

        <div style="padding:15px;">
           <?php require_once("views/body.inc.php"); ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php require_once('views/footer.inc.php'); ?>

</body>

</html>
