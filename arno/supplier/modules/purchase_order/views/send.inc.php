<script>

    var product_data = [
    <?php for($i = 0 ; $i < count($products) ; $i++ ){?>
        {
            product_id:'<?php echo $products[$i]['product_id'];?>',
            product_code:'<?php echo $products[$i]['product_code'];?>',
            product_name:'<?php echo $products[$i]['product_name'];?>'
        },
    <?php }?>
    ];

    function check(){


        var purchase_order_type = document.getElementById("purchase_order_type").value;
        var supplier_id = document.getElementById("supplier_id").value;
        var purchase_order_code = document.getElementById("purchase_order_code").value;
        var purchase_order_date = document.getElementById("purchase_order_date").value;
        var purchase_order_credit_term = document.getElementById("purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;
        
        purchase_order_type = $.trim(purchase_order_type);
        supplier_id = $.trim(supplier_id);
        purchase_order_code = $.trim(purchase_order_code);
        purchase_order_date = $.trim(purchase_order_date);
        purchase_order_credit_term = $.trim(purchase_order_credit_term);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(purchase_order_date.length == 0){
            alert("Please input purchase Order Date");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
               ยืนยันใบสั่งซื้อสินค้า / Confirm Purchase Order 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=update_sending&id=<?php echo $purchase_order_id;?>" >
                    <input type="hidden"  id="purchase_order_id" name="purchase_order_id" value="<?php echo $purchase_order_id; ?>" />
                    <input type="hidden"  id="supplier_id" name="supplier_id" value="<?php echo$purchase_order['supplier_id']; ?>" />
                    <input type="hidden"  id="employee_id" name="employee_id" value="<?php echo$purchase_order['employee_id']; ?>" />
                    <input type="hidden"  id="purchase_order_date" name="purchase_order_date" value="<?php echo $purchase_order['purchase_order_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <select  class="form-control select" onchange="get_supplier_detail()" data-live-search="true" disabled>
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $purchase_order['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รหัสใบสั่งซื้อสินค้า / Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <input id="purchase_order_code" name="purchase_order_code" class="form-control" value="<? echo $purchase_order['purchase_order_code'];?>" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อสินค้า / Purchase Order Date</label>
                                        <input type="text" id="purchase_order_date" name="purchase_order_date" value="<? echo $purchase_order['purchase_order_date'];?>"  class="form-control" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เครดิต (วัน) / Credit term (Day)</label>
                                        <input type="text" id="purchase_order_credit_term" name="purchase_order_credit_term" value="<? echo $purchase_order['purchase_order_credit_term'];?>" readonly class="form-control"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ออกใบสั่งซื้อ / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select  class="form-control select" data-live-search="true" disabled>
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?php if($users[$i]['user_id'] == $purchase_order['employee_id']){?> selected <?php }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จัดส่งโดย /Delivery by</label>
                                        <input type="text" id="purchase_order_delivery_by" name="purchase_order_delivery_by" value="<? echo $purchase_order['purchase_order_delivery_by'];?>"  class="form-control"/>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="150">รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;" >ชื่อสินค้า / หมายเหตุ <br> (Product Name / Remark)</th>
                                <th style="text-align:center;" width="120">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="120">ราคาต่หน่วย <br> (Unit price)</th>
                                <th style="text-align:center;" width="120">จำนวนเงิน <br> (Amount)</th>
                                <th style="text-align:center;" width="120">วันที่จัดส่ง <br> (Delivery)</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="purchase_order_list_id[]"  value="<?php echo $purchase_order_lists[$i]['purchase_order_list_id']; ?>" />
                                    <?PHP  echo $purchase_order_lists[$i]['product_code']; ?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name" readonly value="<?php echo $purchase_order_lists[$i]['product_name']; ?>" />
                                    <span>Remark</span>
                                    <input type="text" class="form-control"  name="purchase_order_list_remark" readonly value="-" />
                                    <span>Supplier remark</span>
                                    <input type="text" class="form-control" style="border-color: coral;" name="purchase_order_list_supplier_remark[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_remark']; ?>" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="purchase_order_list_qty" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_qty']; ?>" />
                                    <input type="text" class="form-control" style="text-align: right;border-color: coral;" onchange="update_sum(this);" name="purchase_order_list_supplier_qty[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_qty']; ?>" />
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="purchase_order_list_price" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_price']; ?>" />
                                    
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="purchase_order_list_price_sum" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_price_sum']; ?>" />
                                
                                </td>
                                <td>
                                    <input type="text" class="form-control "  name="purchase_order_list_delivery_min" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']; ?>" />
                                    <input type="text" class="form-control calendar" style="border-color: coral;" name="purchase_order_list_supplier_delivery_min[]" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_min']; ?>" />
                                </td>
                               <!-- <td>
                                    <input type="text" class="form-control "  name="purchase_order_list_delivery_max" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_max']; ?>" />
                                    <input type="text" class="form-control calendar" style="border-color: coral;" name="purchase_order_list_supplier_delivery_max[]" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_max']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control"  name="purchase_order_list_remark" readonly value="<?php echo $purchase_order_lists[$i]['purchase_order_list_remark']; ?>" />
                                    <input type="text" class="form-control" style="border-color: coral;" name="purchase_order_list_supplier_remark[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_remark']; ?>" />
                                </td>
                               -->
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td>
                                    
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!--<td></td>
                                <td></td>-->
                                
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right"> 
                            <a class="btn btn-danger" href="../admin/print.php?app=purchase_order&action=pdf&lan=supplier&id=<?php echo $purchase_order_id;?>" target="_blank" > <i class="fa fa-print" aria-hidden="true"></i> Print </a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                          
                            <?php 
                            if( $purchase_order['purchase_order_status'] == 'Sending'){
                            ?>
                              <button type="submit" class="btn btn-success">Confirm</button>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>