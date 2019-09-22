<!-- jQuery -->
    <script src="../template/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../template/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../template/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../template/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../template/vendor/datatables-responsive/dataTables.responsive.js"></script>

    
     

    <!-- Custom Theme JavaScript -->
    <script src="../template/dist/js/sb-admin-2.js"></script>

    <!-- Custom Dropdown Theme JavaScript -->
    <script src="../template/dist/js/bootstrap-select.min.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->


    <script src="../template/dist/js/jquery-ui.js"></script>

    <!-- Morris Charts JavaScript -->
    <?php if($_GET['app'] =="" ){?>
    <script src="../template/vendor/raphael/raphael.min.js"></script>
    <script src="../template/vendor/morrisjs/morris.min.js"></script>
    <script src="../template/data/morris-data.js"></script>
    <?php }?>
    <script>

    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
        $('#tb-product-customer').DataTable({
            responsive: true
        });
        $('.select').selectpicker();
    });


    $( function() {
        $( "#customer_holiday_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#stock_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_start" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_end" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );



    $( function() {
        $( "#draggable" ).draggable();
    } );

    </script>