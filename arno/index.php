<?

session_start();
 
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132661003-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-132661003-1');
    </script>

    <title> ERP System Demo</title>

    <!-- Bootstrap Core CSS -->
    <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="template/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="template/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="template/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>

        function refresh(){
        <?php 
            if($_SESSION['url']!=''){ 
        ?>
            window.location = "<?PHP echo $_SESSION['url']; ?>";
        <?php 
           } else{
        ?>
            window.location = "admin/index.php"
        <?php 
            }
        ?>
        }

        function error(){
            alert("Can not login. Please check your username and password.");
            document.getElementById("error").innerHTML = "username and password.";
        }

        function check(){
            
            var user = document.getElementById("username").value;
            var pass = document.getElementById("password").value;
            
            if(user == ""){
                alert("Please input username.");
                document.getElementById("error").innerHTML = "Please input username.";
                return false;
            }else if (pass == ''){
                alert("Please input password.");
                document.getElementById("error").innerHTML = "Please input password.";
                return false;
            }

            return true;

        }

    </script>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading" align="center">
                        <h3 class="panel-title"><?PHP echo $company['company_name_en']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div align="center" style="padding:8px;"><img src="upload/company/<?PHP echo $company['company_image']; ?>" height="96px" /></div>
                        <iframe id="checklogin" name="checklogin" src="" style="width:0px;height:0px;border:0"></iframe>
                        <form role="form" method="post" action="check_login.php" onSubmit="return check();" target="checklogin">
                            <fieldset>
                                <div class="form-group" method="post" action="">
                                    <input class="form-control" placeholder="Username" id="username" name="username" type="text" autocomplete='off' autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
                                </div>
                                <div align="center" id="error" name="error" style="color:#F00;padding:8px;">
                                    
                                </div>
                                <!--
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" name="login_btn" class="btn btn-lg btn-danger btn-block">Login</button>
                                <a href="../" class="btn btn-lg btn-default btn-block" >Go to home</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="template/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="template/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="template/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="template/dist/js/sb-admin-2.js"></script>

</body>

</html>
