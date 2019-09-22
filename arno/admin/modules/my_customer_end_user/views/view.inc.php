<script>
    function check(){   
        var end_user_id = document.getElementById("end_user_id").value;
       
        end_user_id = $.trim(end_user_id);
        
       if(end_user_id.length == 0){
            alert("Please select end user.");
            document.getElementById("end_user_id").focus();
            return false;
        }else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Customer End User Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>






<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Customer  Information. 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                    <label>Customer code  </label>
                                    <p class="help-block"><? echo $customer['customer_code']?></p>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Customer name (Thai)  </label>
                                    <p class="help-block"><? echo $customer['customer_name_th']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Customer name (English) </label>
                                    <p class="help-block"><? echo $customer['customer_name_en']?></p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Customer Type </label>
                                        <p class="help-block"><? echo $customer['customer_type']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. </label>
                                        <p class="help-block"><? echo $customer['customer_tax']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic </label>
                                    <p class="help-block"><? echo $customer['customer_domestic']?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p class="help-block"><? echo $customer['customer_branch']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <p class="help-block"><? echo $customer['customer_zone']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <p class="help-block"><? echo $customer['credit_day']?> วัน</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <p class="help-block"><? echo $customer['condition_pay']?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <p class="help-block"><? echo $customer['customer_tel']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <p class="help-block"><? echo $customer['customer_fax']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <p class="help-block"><? echo $customer['customer_email']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <p class="help-block"><? echo $customer['customer_address_1']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <p class="help-block"><? echo $customer['customer_address_2']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <p class="help-block"><? echo $customer['customer_address_3']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <p class="help-block"><? echo $customer['customer_zipcode']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Customer Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/customer/<?php if($customer['customer_logo'] != ''){echo $customer['customer_logo'];}else{echo 'default.png';}  ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายการลูกค้าปลายทาง / End users list
                    </div>
                    <div class="col-md-4" align="right"> 
                            <a class="btn btn-success " style="margin-left:4px;" href="?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add new end user</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <?php if($license_manager_page == "Medium" || $license_manager_page == "High"){ ?> 
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=my_customer_end_users&action=addEnduser&customer_id=<?PHP echo $customer_id; ?>" enctype="multipart/form-data">                             
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ผู้ซื้อ </label>
                                <select id="end_user_id" name="end_user_id" class="form-control select" <?php if($license_manager_page != "Medium" && $license_manager_page != "High"){ echo'disabled'; } ?> data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option value="<?php echo $customers[$i]['customer_id'] ?>">[<?php echo $customers[$i]['customer_code'] ?>] <?php echo $customers[$i]['customer_name_en'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" onclick="check_login('form_target');" class="btn btn-primary" style=" margin:0px 4px;" ><i class="fa fa-arrow-down" aria-hidden="true"></i> Add End User ในรายการ</button> 
                        </div>
                        <div class="col-md-4">
                        </div>
                        
                    </div>
                </form>
            <? } ?>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="dataTables-example_filter" class="dataTables_filter">
                            
                        </div>
                    </div>
                </div>

                    <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer_end_users),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th style="text-align:center">ลำดับ <br>No.</th>
                                    <th style="text-align:center">รหัสลูกค้า <br>Code</th>
                                    <th style="text-align:center">ชื่อไทย <br>Name thai</th>
                                    <th style="text-align:center">ชื่ออังกฤษ <br>Name english</th>
                                    <th style="text-align:center">เลขผู้เสียภาษี <br>TAX ID</th>
                                    <th style="text-align:center">โทรศัพท์ <br>Mobile</th>
                                    <th style="text-align:center">อีเมล์ <br>Email</th>
                                    <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Status" width="80"> สถานะ</th>
                                    <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="สถานะการค้า" width=""> สถานะการค้า</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($customer_end_users); $i++){
                                ?>
                                <tr class="odd gradeX <?php if( $customer_end_users[$i]['check_limit'] > $customer_end_users[$i]['customer_register_relimit'] && $customer_end_users[$i]['customer_register_status'] == 'ลงทะเบียน' ){ 
                                        echo 'danger';
                                    }else if($customer_end_users[$i]['customer_register_status'] == 'ขาย' ){
                                        echo 'success';
                                    }else if($customer_end_users[$i]['customer_register_status'] == 'ทดสอบ' ){
                                        echo 'active';
                                    } else if($customer_end_users[$i]['customer_register_status'] == 'ยกเลิก' ){
                                        echo 'danger';
                                    } ?>
                                ">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_end_users[$i]['customer_code']; ?></td>
                                    <td><?php echo $customer_end_users[$i]['customer_name_th']; ?></td>
                                    <td><?php echo $customer_end_users[$i]['customer_name_en']; ?></td>
                                    <td><?php echo $customer_end_users[$i]['customer_tax']; ?></td>
                                    <td class="center"><?php echo $customer_end_users[$i]['customer_tel']; ?></td>
                                    <td class="center"><?php echo $customer_end_users[$i]['customer_email']; ?></td>
                                    <td class="center">
                                        <?PHP 
                                            if($customer_end_users[$i]['customer_approve'] == "New"){   
                                                $font_color = "#327AB7";
                                            } else if ($customer_end_users[$i]['customer_approve'] == "Request"){   
                                                $font_color = "#ffc107";
                                            } else if ($customer_end_users[$i]['customer_approve'] == "Approved"){   
                                                $font_color = "#5CB85C";
                                            }
                                        ?>
                                        <b>
                                            <font color="<?PHP echo $font_color; ?>">
                                                <?php echo $customer_end_users[$i]['customer_approve']; ?>
                                            </font>
                                        </b>
                                    </td>
                                    <td>
                                    <?php echo $customer_end_users[$i]['customer_register_status']; ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-default" title="View Detail" href="?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&action=detail&id=<?php echo $customer_end_users[$i]['customer_id'];?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a> 
                                        <a  class="btn btn-default" title="Update data" href="?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&action=update&id=<?php echo $customer_end_users[$i]['customer_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?php if($license_manager_page == "Medium" || $license_manager_page == "High" || $customer_end_users[$i]['customer_approve'] == "New"){ ?>  
                                            <?php if($license_manager_page == "High"){ ?> 
                                                <a class="btn btn-default" title="Delete data" href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&action=delete&id=<?php echo $customer_end_users[$i]['customer_id'];?>" onclick="return confirm('You want to delete customer : <?php echo $customer_end_users[$i]['customer_name_th']; ?>');" style="color:red;">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            <?PHP } ?>
                                        <?PHP }?>
                                    </td>
                                </tr>
                            <?
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer_end_users),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=my_customer_end_users&customer_id=<?PHP echo $customer_id; ?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="col-md-4" align="right"> 
                    </div>
                    <div class="col-md-4" align="right"> 
                    </div> 
                    <div class="col-md-4" align="right">
                        <a href="?app=my_customer" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


