<script>
    function set_status(status){
        $('#purchase_order_accept_status').val(status); 
    }

    function update_sum(id){

var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val(  ).replace(',',''));
var price =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( ).replace(',',''));
var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( ).replace(',',''));

if(isNaN(qty)){
  qty = 0;
}

if(isNaN(price)){
  price = 0.0;
}

if(isNaN(sum)){
  sum = 0.0;
}

sum = qty*price;

$(id).closest('tr').children('td').children('input[name="purchase_order_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
$(id).closest('tr').children('td').children('input[name="purchase_order_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
$(id).closest('tr').children('td').children('input[name="purchase_order_list_price_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

calculateAll();


}


function calculateAll(){

var val = document.getElementsByName('purchase_order_list_price_sum[]');
var total = 0.0;

for(var i = 0 ; i < val.length ; i++){
    
    total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
}

$('#purchase_order_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
$('#purchase_order_vat_price').val((total * ($('#purchase_order_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
$('#purchase_order_net_price').val((total * ($('#purchase_order_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

}
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Purchase Order Management <b style="color:red;">[
                <?PHP echo  $purchase_order['purchase_order_type'];?>]</b> <b>[ <font color="#F00">Cancelled</font>]</b> </h1>
    </div> 
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <div class="col-md-6">  
                    Purchase Order Cancelled Detail
                </div>
                <div class="col-md-6 " align="right" > 
                    <a class="btn btn-warning" href="../admin/print.php?app=purchase_order&action=pdf&id=<?PHP echo $purchase_order['purchase_order_id'];?>"
                        target="_blank" title="Print" style="color:white;">
                        <i class="fa fa-print" aria-hidden="true"></i> Print
                    </a>
                </div>
            </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Type </label>
                                        <p class="help-block">
                                            <?php echo $purchase_order['purchase_order_type']?> 
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Supplier Code </label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_code']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Supplier </label>
                                        <p class="help-block"><?php echo $purchase_order['supplier_name_en'] ?> </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address </label>
                                        <p class="help-block">
                                            <?php echo $purchase_order['supplier_address_1']?><br><?php echo $purchase_order['supplier_address_2']?><br><?php echo $purchase_order['supplier_address_3']?><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Code  
                                        <?php if($purchase_order['purchase_order_rewrite_no'] > 0){ ?><b>
                                                <font color="#F00">Revise
                                                    <?PHP echo $purchase_order['purchase_order_rewrite_no']; ?>
                                                </font>
                                            </b>
                                            <?PHP } ?> <?php if($purchase_order['purchase_order_cancelled'] == 1){ ?><b>
                                                <font color="#F00">Cancelled</font>
                                            </b>
                                            <?PHP } ?>
                                        
                                        </label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_code']?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <form method="post" name="from2">
                                        <div class="form-group">
                                            <label>Purchase Order Code Online </label>
                                            <div class="row">
                                                <div class="col-lg-8"> 
                                                    <p class="help-block"><?php echo $purchase_order['purchase_order_code_online']?></p>
                                                </div> 
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_date']?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_credit_term']?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee <font color="#F00"><b>*</b></font> </label>
                                        <p class="help-block"><?php echo $purchase_order['user_name'] ?>
                                            <?php echo $purchase_order['user_lastname'] ?>(<?php echo $purchase_order['user_position_name'] ?>)
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <p class="help-block"><?php echo $purchase_order['purchase_order_delivery_by']?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        Our reference :
                    </div>
                    
                <form  role="form" method="post" action="index.php?app=purchase_order&action=save_price&id=<?php echo $purchase_order_id;?>">
                    <table width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="text-align:center;width:32px;">Item</th>
                                <th style="text-align:center;">Product Code</th>
                                <th style="text-align:center;">Product Name / Description</th>
                                <th style="text-align:center;">Purchase detail</th>
                                <th style="text-align:center;">Order Qty</th>
                                <th style="text-align:center;">Recieve</th> 
                                <th style="text-align:center;"> @ </th>
                                <th style="text-align:center;">Amount</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
							$sub_total = 0;
                            for($i=0; $i < count($purchase_order_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <?php echo $i + 1; ?>.
                                </td>
                                <td>
                                    <?php echo $purchase_order_lists[$i]['product_code']?>
                                </td>
                                <td>
                                    Product name : <?php echo $purchase_order_lists[$i]['product_name']?> <br>
                                    Delivery :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_min']?> -
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_delivery_max']?> <br>
                                    Remark : <?php echo $purchase_order_lists[$i]['purchase_order_list_remark']?> <br>
                                    <br><label>Supplier Confirm</label><br>
                                    Qty : <?php  echo $purchase_order_lists[$i]['purchase_order_list_supplier_qty']?>
                                    <br>
                                    Delivery Date :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_min']?>
                                    -
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_delivery_max']?>
                                    <br>
                                    Supplier Remark :
                                    <?php echo $purchase_order_lists[$i]['purchase_order_list_supplier_remark']?> <br>
                                </td>



                                <td>
                                    <?PHP   
                                    //  echo  $purchase_order_lists[$i]['purchase_order_list_id'];
                                    $invoice_product = $purchase_order_model -> getPurchaseOrderInvoiceProductBy($purchase_order_lists[$i]['purchase_order_list_id']); 

                                    for($j = 0; $j<count($invoice_product); $j++){ 
                                            # code...
                                        
                                        ?>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <a href="index.php?app=invoice_supplier&action=detail&id=<?PHP echo $invoice_product[$j]['invoice_supplier_id']; ?>"
                                                target="_blank">
                                                <?PHP
                                                echo $invoice_product[$j]['invoice_supplier_code_gen']; 
                                                ?>
                                            </a>
                                            จำนวน
                                            <?PHP
                                                echo $invoice_product[$j]['invoice_supplier_list_qty']; 
                                                ?>
                                            pcs
                                        </li>
                                    </ul>
                                    <?PHP 
                                    
                                }
                                    
                                    ?>
                                </td>


                                <td align="right">
                                
                                <input type="hidden" name="purchase_order_list_id[]" value="<?PHP echo $purchase_order_lists[$i]['purchase_order_list_id']; ?>"/>
                                <input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_qty[]" value="<?php echo $purchase_order_lists[$i]['purchase_order_list_qty']; ?>" />
                                </td>
                                
                                <td align="right">
                                    <?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty_recieve'],0)?>
                                </td>

                               <td>
                                    <input readonly type="text" class="form-control" style="text-align: right;" autocomplete="off"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" /> 
                                </td>
                                <td align="right">
                                    <input type="text" class="form-control" style="text-align: right;" autocomplete="off" readonly onchange="update_sum(this);" name="purchase_order_list_price_sum[]" value="<?php echo number_format($purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'],2); ?>" />
                                </td>
                                 
                            </tr>
                            <?
							$sub_total += $purchase_order_lists[$i]['purchase_order_list_price_sum'];
                            $total += $purchase_order_lists[$i]['purchase_order_list_qty'] * $purchase_order_lists[$i]['purchase_order_list_price'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="5" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span> Sub total</span>
                                </td>
                                <td>
                                <?PHP
                                    if($supplier['vat_type'] == 1){
                                        $total_val = $total - (($supplier['vat']/( 100 + $supplier['vat'] )) * $total);
                                    } else if($supplier['vat_type'] == 2){
                                        $total_val = $total;
                                    } else {
                                        $total_val = $total;
                                    }
                                ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_total_price" name="purchase_order_total_price" value="<?PHP echo number_format($total_val,2) ;?>"  readonly/>
                                </td> 
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span> Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input readonly type="text" class="form-control" style="text-align: right;" id="purchase_order_vat" name="purchase_order_vat" value="<?php echo $supplier['vat'];?>" onchange="calculateAll();" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td>
                                    <?PHP 
                                    if($supplier['vat_type'] == 1){
                                        $vat_val = ($supplier['vat']/( 100 + $supplier['vat'] )) * $total;
                                    } else if($supplier['vat_type'] == 2){
                                        $vat_val = ($supplier['vat']/100) * $total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_vat_price"  name="purchase_order_vat_price" value="<?PHP echo number_format($vat_val,2) ;?>"  readonly/>
                                </td> 
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span> Net Total</span>
                                </td>
                                <td>
                                    <?PHP 
                                    if($supplier['vat_type'] == 1){
                                        $net_val =  $total;
                                    } else if($supplier['vat_type'] == 2){
                                        $net_val = ($supplier['vat']/100) * $total + $total;
                                    } else {
                                        $net_val = $total;
                                    }
                                    ?>
                                    <input type="text" class="form-control" style="text-align: right;" id="purchase_order_net_price" name="purchase_order_net_price" value="<?PHP echo number_format($net_val,2) ;?>" readonly/>
                                </td> 
                            </tr>
                        </tfoot>
                    </table>
                </form>
                    
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-4" align="right"> 
                           </div>
                        <div class="col-lg-8" align="right">   
                        </div>
                        
                    </div>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>