    <a id="btn_go_to_top"></a>

    <!-- Bootstrap Core JavaScript -->
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../template/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../template/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../template/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../template/vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js" ></script>
    
     

    <!-- Custom Theme JavaScript -->
    <script src="../template/dist/js/sb-admin-2.js"></script>

    <!-- Custom Dropdown Theme JavaScript -->
    <script src="../template/dist/js/bootstrap-select.min.js"></script>
    

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->

    <script src="../lib/functions.js"></script>
    <script src="../template/dist/js/totop.js"></script>

    <!-- Morris Charts JavaScript -->
    <?php if($_GET['app'] =="" ){?>
    <!-- <script src="../template/vendor/raphael/raphael.min.js"></script>
    <script src="../template/vendor/morrisjs/morris.min.js"></script>
    <script src="../template/data/morris-data.js"></script> -->
    <script src="../template/dist/js/numeral.min.js"></script>
    <script src="../template/dist/js/Chart.min.js"></script>
    <script src="../template/dist/js/Chart.bundle.min.js"></script>
    
    <?php }?>

    <script>




    $(document).ready(function() {
        $('#FIA').DataTable({
            "lengthMenu": [[5],[5]],
            "pageLength": 5,
            responsive: true 
        });
        
        $('#FIB').DataTable({
            "lengthMenu": [[5],[5]],
            "pageLength": 5,
            responsive: true 
        });
        
        $('#FIC').DataTable({
            "lengthMenu": [[5],[5]],
            "pageLength": 5,
            responsive: true 
        });
        
        $('#FID').DataTable({
            "lengthMenu": [[5],[5]],
            "pageLength": 5,
            responsive: true 
        });
        
        $('#FIF').DataTable({
            "lengthMenu": [[5],[5]],
            "pageLength": 5,
            responsive: true 
        });
        
        $('#TEN').DataTable({
            "lengthMenu": [[10],[10]],
            "pageLength": 10,
            responsive: true 
        });

        $('#TENTEN').DataTable({
            "lengthMenu": [[10],[10]],
            "pageLength": 10,
            responsive: true 
        });
        $('#dataTables-view').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true 
        });

        $('#dataTables-example').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#dataTables-product').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#dataTables-quotation').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#dataTables-order').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#dataTables-invoice').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#tb-product-customer').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('#tb-popup').DataTable({
            "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
            "pageLength": 100,
            responsive: true
        });

        $('.select').selectpicker();
        <?PHP if(($_GET['app'] == "employee" || $_GET['app'] == "regrind_supplier") && ($_GET['action'] == 'update' || $_GET['action'] == 'insert'|| $_GET['action'] == 'detail') ){ ?>

        var c = document.getElementById("signature");
        var ctx = c.getContext("2d");
        var img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0);
        };
        <?PHP if($_GET['app'] == "employee"){?>
            img.src = '<?PHP echo $user['user_signature'];?>';
        <?PHP } ?>

        <?PHP if($_GET['app'] == "regrind_supplier"){?>
            img.src = '<?PHP echo $regrind_supplier['contact_signature'];?>';
        <?PHP } ?>

        var canvas = document.getElementById("signature");
        var signaturePad = new SignaturePad(canvas);
        
        $('#clear-signature').on('click', function(){
            signaturePad.clear();
        });

        <?PHP } ?>
        

    });



    $( function() {
        $( "#customer_holiday_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#stock_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_start" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_end" ).datepicker({ dateFormat: 'dd-mm-yy' }); 
        $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $("[data-toggle='tooltip']").tooltip();
    } );


</script>


<style>    
div.dataTables_filter{
    display: none;
}
</style>

<script>

    $( function() {

        

        $( "#draggable" ).draggable();
    } );

    
    </script>



    <!-- Modal for use check login --->
    
<script type="text/javascript">
    function login(){
        var username = document.getElementById("username").value;
        var psw = document.getElementById("psw").value;
        var userold = document.getElementById("userold").value;
        username = $.trim(username);
        psw = $.trim(psw);
        userold = $.trim(userold);
        if(userold == username){
            if(username.length == 0){
                alert("Please input username.");
                document.getElementById("username").focus();
                return false;
            }else if(psw.length == 0){
                alert("Please input password.");
                document.getElementById("psw").focus();
                return false;
            }else{
                
                $.post("controllers/checkLogin.php", {username:username,password:psw,userold:userold}, function(data){
                    if(data.result == true){
                        $("#mylogin").modal('hide');
                    }else {
                        alert("username or password invalid.");
                    }
                });
            }
        }
        else {
            alert("User invalid.")
        }
    }
    $(document).ready(function(){
        $('#psw').keypress(function(event){
            
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                login();	
            }

        });
    });


    function check_login(form_name){
        $.post("controllers/checkSession.php", {}, function(data){
            //console.log(data.result); 
            if(data.result == true){
                $("#"+form_name).submit();
            }else {
                $("#mylogin").modal();
            }
        });


    }

    function check_permission(url,div_id){
        $.post(url, {}, function(data){
            console.log(data); 
            if(data.result == true){ 
                $(div_id).show();
            }else { 
                $(div_id).hide();
            }
        });


    }   


    function switch_login(url,link_url){
        $.post(url, {}, function(data){
            console.log(data); 
            if(data.result == true){ 
                window.location=link_url;
            } 
        });


    }   

    <?PHP if($company['company_code'] != 'AR'){ ?>
    check_permission("../../arno/admin/controllers/checkLoginPermission.php","#link_arno");
    <?PHP } ?>

    <?PHP if($company['company_code'] != 'PC'){ ?>
    check_permission("../../partner-chips/admin/controllers/checkLoginPermission.php","#link_partner_chips");
    <?PHP } ?>

    <?PHP if($company['company_code'] != 'BM'){ ?>
    check_permission("../../best-machine/admin/controllers/checkLoginPermission.php","#link_best_machine");
    <?PHP } ?>

    <?PHP if($company['company_code'] != 'TM'){ ?>
    check_permission("../../tool-management/admin/controllers/checkLoginPermission.php","#link_tool_management");
    <?PHP } ?>

    //check_permission("../../tool-management/admin/controllers/checkLoginPermission.php","#link_arno");


    
</script>

<!-- Trigger the modal with a button -->
<!-- Modal -->
<div class="modal fade" id="mylogin" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body" style="padding:40px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
                <div align="center" style="padding:8px;"><img class="img-responsive logo" src="../upload/company/<?PHP echo $company['company_image']; ?>"></div>
                <div class="form-group">
                    <label for="usrname"><span class="glyphicon glyphicon-user"></span> Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter username">
                    <input type="hidden" id="userold" value="<?php echo $_SESSION['user']['user_username'] ?>" >
                </div>
                <div class="form-group">
                    <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
                    <input type="password" class="form-control" id="psw" placeholder="Enter password" >
                </div>
                <button type="button" onclick="login();" class="btn btn-danger btn-block"><span class="fa fa-sign-in"></span> Login</button>
            </div>
        </div>

    </div>
</div> 
    <!-- /Modal for use check login --->