<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
    
        <a href="?app=employee" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <a href="?app=employee_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a>
    
    </div>
    <!-- /.col-lg-12 -->
</div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    รายชื่อพนักงาน / Employee List
                                </div>
                                <div class="col-md-4">
                                    <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=employee&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="24"> No.</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัส" width="80"> ID</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อ" width="200"> Name</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ตำแหน่ง" width="200"> Position</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="โทรศัพท์" width="100"> Mobile</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="อีเมล์" width="200"> Email</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="สถานะ" width="100"> Status</th>
                                        <th  width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($user); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center;"><?php echo $i+1; ?></td>
                                        <td ><?php echo $user[$i]['user_code']; ?></td>
                                        <td><?php echo $user[$i]['name']; ?></td>
                                        <td><?php echo $user[$i]['user_position_name']; ?></td>
                                        <td class="center"><?php echo $user[$i]['user_mobile']; ?></td>
                                        <td class="center"><?php echo $user[$i]['user_email']; ?></td>
                                        <td style="text-align:center;"class="center"><?php echo $user[$i]['user_status_name']; ?></td>
                                        <td style="text-align:center;">
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a class="btn btn-default" href="?app=employee&action=update&id=<?php echo $user[$i]['user_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a class="btn btn-default" href="?app=employee&action=delete&id=<?php echo $user[$i]['user_id'];?>" onclick="return confirm('You want to delete employee : <?php echo $user[$i]['name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP }?>
                                        </td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
