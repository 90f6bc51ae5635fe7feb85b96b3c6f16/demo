    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Revel Soft - ERP System</title>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132661003-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-132661003-1');
    </script>


    <!-- Bootstrap Core CSS -->
    <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../template/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../template/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Dropdown CSS -->
    <link href="../template/dist/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../template/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="../template/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../template/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">



    <link rel="stylesheet" href="../template/dist/css/jquery-ui.css">


    <!-- Main CSS -->
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/notification.css" rel="stylesheet">
    <link href="../css/report.css" rel="stylesheet">
    <link href="../css/totop.css" rel="stylesheet">


    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    

    <script src="../template/dist/js/jquery-ui.js"></script>
    

    <!-- JS file -->
    <script src="../plugins/autocomplete/jquery.easy-autocomplete.min.js"></script> 
    <!-- CSS file -->
    <link rel="stylesheet" href="../plugins/autocomplete/easy-autocomplete.min.css"> 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
            appId: "430c4d4b-cd09-413b-909a-56a3284dc108",
            });
        });
    </script>

    <script>
        $(document).ready(function(){ 
            console.log("OneSignal User ID:");
            OneSignal.push(function() {
                OneSignal.getUserId(function(userId) {
                    console.log("OneSignal User ID:", userId); 
                    $.post( "controllers/updatePlayerIDByID.php", { 'user_id':'<?PHP echo $admin_id ?>','user_player_id': userId}, function( data ) {
                        console.log("result : ",data);
                        if(data == true){
                            console.log("Set player id complete.");
                        }else{
                            console.log("Set player id fail.");
                        }
                    });
                });
            });
        }); 
    </script>

