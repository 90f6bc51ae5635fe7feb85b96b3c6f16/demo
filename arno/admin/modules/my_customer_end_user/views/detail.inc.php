<div class="row">
    <!-- /.col-lg-12 -->
     <div class="col-lg-12" align="center">
        <h1 class="page-header"><?PHP    echo  $header_page?></h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <?PHP echo  $header_page?>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="?app=my_customer_end_users&customer_id=<?php echo $customer_id;?>" class="btn btn-default align-right">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                <div class="row">

                <!-- รูปสินค้า -->
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูป / Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/customer/<?PHP   if($end_user_id['customer_logo'] != ""){ echo  $end_user_id['customer_logo']; }else{ echo  "default.png"; } ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <div class="col-lg-1">
                            <label>	รหัสลูกค้า / Code</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_code'];?></p>
                    </div>

                    <div class="col-lg-3">
                            <label>ชื่อลูกค้า / Name</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_name_en'];?></p>
                            <p class="help-block">(<?PHP    echo  $end_user_id['customer_name_th'];?>)</p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>ประเภทบริษัท / Type</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_type'];?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>เลขผู้เสียภาษี / TAX </label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_tax'];?></p>
                    </div>

                    <div class="col-lg-8">
                            <label>ที่อยู่ / Address </label>
                            <p class="help-block"><?PHP   
                                echo  $end_user_id['customer_address_1'];
                                echo  " ";
                                echo  $end_user_id['customer_address_2'];
                                echo  " ";
                                echo  $end_user_id['customer_address_3']; 
                                echo  " ";
                                echo  $end_user_id['customer_zipcode'];
                             ?>
                             </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เบอร์โทรศัพท์ / TEL</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_tel'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เบอร์แฟค / FAX </label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_fax'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	อีเมล / Email</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_email'];?></p>
                    </div>                                       
                    
                    <div class="col-lg-2">
                            <label>	บริษัทของประเทศ / Domestic </label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_domestic'];?></p>
                    </div>
                    
                    <!-- <div class="col-lg-2">
                            <label>รายละเอียด / Description</label>
                            <p class="help-block"><?PHP
                                if ($end_user_id['customer_remark'] === null || "" || " ") {
                                    echo "-";
                                }else {
                                    echo  $end_user_id['customer_remark'];
                                }
                                ?>
                            </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>สาขา / Branch</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_branch'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เขตการขาย / Zone</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_zone'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เครดิตการจ่าย / Credit</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['credit_day'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เงื่อนไขการชำระเงิน / Condition</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['condition_pay'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	วงเงินอนุมัติ / Pay Limit</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['pay_limit'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทบัญชี	 / Account</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['account_name_th'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทภาษีมูลค่าเพิ่ม / Vat Type</label>
                            <p class="help-block">
                            <?PHP  
                            if($end_user_id['vat_type'] == 0){
                                echo  "0 - ไม่มี Vat";
                            }else if($end_user_id['vat_type'] == 1){
                                echo  "1 - รวม Vat";
                            }else if($end_user_id['vat_type'] == 2){
                                echo  "2 - แยก Vat";
                            }
                            
                            ?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ภาษีมูลค่าเพิ่ม	 / Vat</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['vat'];?>%</p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	สกุลเงิน / Currency</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['currency_name'];?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>	กลุ่มลูกค้า / Customer group</label>
                            <p class="help-block"><?PHP    echo  $end_user_id['customer_group_name'];?></p>
                    </div> -->
                           
                </div>
                <!-- /.panel-row -->                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


<script>
$(document).ready(function(){
    $('#dataTables-product').DataTable({
        "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
        "pageLength": 100,
        responsive: true
    });
});

$(document).ready(function(){
    $('#dataTables-invoice').DataTable({
        "lengthMenu": [[25, 50, 75, 100, 250, 500, -1 ],[25, 50, 75, 100, 250, 500, 'All' ]],
        "pageLength": 100,
        responsive: true
    });
});
</script>


