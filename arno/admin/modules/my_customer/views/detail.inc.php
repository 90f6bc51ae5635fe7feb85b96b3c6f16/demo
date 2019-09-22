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
                        <?PHP    echo  $header_page?>
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
                                    <img class="img-responsive" id="img_logo" src="../upload/customer/<?PHP   if($customer['customer_logo'] != ""){ echo  $customer['customer_logo']; }else{ echo  "default.png"; } ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <div class="col-lg-1">
                            <label>	รหัสลูกค้า / Code</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_code'];?></p>
                    </div>

                    <div class="col-lg-3">
                            <label>ชื่อลูกค้า / Name</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_name_en'];?></p>
                            <p class="help-block">(<?PHP    echo  $customer['customer_name_th'];?>)</p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>ประเภทบริษัท / Type</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_type'];?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>เลขผู้เสียภาษี / TAX </label>
                            <p class="help-block"><?PHP    echo  $customer['customer_tax'];?></p>
                    </div>

                    <div class="col-lg-8">
                            <label>ที่อยู่ / Address </label>
                            <p class="help-block"><?PHP   
                                echo  $customer['customer_address_1'];
                                echo  " ";
                                echo  $customer['customer_address_2'];
                                echo  " ";
                                echo  $customer['customer_address_3']; 
                                echo  " ";
                                echo  $customer['customer_zipcode'];
                             ?>
                             </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เบอร์โทรศัพท์ / TEL</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_tel'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เบอร์แฟค / FAX </label>
                            <p class="help-block"><?PHP    echo  $customer['customer_fax'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	อีเมล / Email</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_email'];?></p>
                    </div>                                       
                    
                    <div class="col-lg-2">
                            <label>	บริษัทของประเทศ / Domestic </label>
                            <p class="help-block"><?PHP    echo  $customer['customer_domestic'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>รายละเอียด / Description</label>
                            <p class="help-block"><?PHP
                                if ($customer['customer_remark'] === null || "" || " ") {
                                    echo "-";
                                }else {
                                    echo  $customer['customer_remark'];
                                }
                                ?>
                            </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>สาขา / Branch</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_branch'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เขตการขาย / Zone</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_zone'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เครดิตการจ่าย / Credit</label>
                            <p class="help-block"><?PHP    echo  $customer['credit_day'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เงื่อนไขการชำระเงิน / Condition</label>
                            <p class="help-block"><?PHP    echo  $customer['condition_pay'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	วงเงินอนุมัติ / Pay Limit</label>
                            <p class="help-block"><?PHP    echo  $customer['pay_limit'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทบัญชี	 / Account</label>
                            <p class="help-block"><?PHP    echo  $customer['account_name_th'];?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทภาษีมูลค่าเพิ่ม / Vat Type</label>
                            <p class="help-block">
                            <?PHP  
                            if($customer['vat_type'] == 0){
                                echo  "0 - ไม่มี Vat";
                            }else if($customer['vat_type'] == 1){
                                echo  "1 - รวม Vat";
                            }else if($customer['vat_type'] == 2){
                                echo  "2 - แยก Vat";
                            }
                            
                            ?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ภาษีมูลค่าเพิ่ม	 / Vat</label>
                            <p class="help-block"><?PHP    echo  $customer['vat'];?>%</p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	สกุลเงิน / Currency</label>
                            <p class="help-block"><?PHP    echo  $customer['currency_name'];?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>	กลุ่มลูกค้า / Customer group</label>
                            <p class="help-block"><?PHP    echo  $customer['customer_group_name'];?></p>
                    </div>
                           
                </div>
                <!-- /.panel-row -->                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                         <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="product-tab" data-toggle="tab" href="#product" role="tab" aria-controls="product" aria-selected="true">สินค้า</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="quotation-tab" data-toggle="tab" href="#quotation" role="tab" aria-controls="quotation" aria-selected="false">ใบเสนอราคา</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="false">ใบสั่งซื้อสินค้า</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false">ใบกำกับภาษี</a>
                            </li>
                         </ul>
                    </div>
                </div>
            </div>
            
            <!-- /.panel-heading -->
            <div class="panel-body">

                <!-- 
                <script>
                    $('#myTab a[href="#product"]').tab('show') // Select tab by name
                    $('#myTab li:first-child a').tab('show') // Select first tab
                    $('#myTab li:last-child a').tab('show') // Select last tab
                    $('#myTab li:nth-child(3) a').tab('show') // Select third tab
                </script> 
                -->
                <script>
                    $(function () {
                        $('#myTab li:first-child a').tab('show') // Select first tab
                    })
                </script>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade "  id="product" role="tabpanel" aria-labelledby="product-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-product">
                            <thead>
                                <tr>
                                    <th style="width:64px;" class="text-center" >ลำดับ</th>
                                    <th class="text-center" >รหัสสินค้า</th>
                                    <th class="text-center" >ชื่อสินค้า</th>
                                    <th class="text-center" >วันที่ขายล่าสุด</th>
                                    <th class="text-center" >ใบกำกับภาษีล่าสุด</th>
                                    <th class="text-center" >ราคาสินค้า</th>
                                </tr>
                            </thead>
                            <tbody class="odd gradeX">
                            <?PHP 
                                for ($i=0; $i < count($product); $i++) { 
                                $invoice_customer = $invoice_customer_model->getInvoiceCustomerLastSaleByProductID($product[$i]["product_id"],$customer_id);
                            ?>
                                <tr>
                                    <td style="text-align:left;"> <?PHP echo $i+1 ?></td>
                                    <td data-order="<?PHP echo $product[$i]['product_code']; ?>" ><a target="_blank" href="index.php?app=product_detail&product_id=<?PHP echo $product[$i]['product_id']  ?>">  <?PHP echo $product[$i]['product_code']  ?> </a></td>
                                    <td> <?PHP echo $product[$i] ['product_name'] ?></td>
                                    <td data-order="<?php echo $timestamp = strtotime(  $invoice_customer['invoice_customer_date']  ) ?>" > <?PHP echo $invoice_customer['invoice_customer_date'] ?></td>
                                    <td data-order="<?PHP echo $invoice_customer['invoice_customer_code']; ?>" > <a target="_blank" href="print.php?app=invoice_customer&action=pdf&id=<?PHP echo $invoice_customer['invoice_customer_id']  ?>"><?PHP echo $invoice_customer['invoice_customer_code'] ?></a></td>
                                    <td  style="text-align:right;" data-order="<?PHP echo $invoice_customer['invoice_customer_list_price']; ?>" > <?PHP echo number_format($invoice_customer['invoice_customer_list_price'] ,2)?></td>
                                </tr>
                            <?PHP 

                                }

                            ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="quotation" role="tabpanel" aria-labelledby="quotation-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-quotation">
                            <thead>
                                <tr>
                                    <th style="width:100px;" > ลำดับ  </th>
                                    <th style="width:100px;" > วันที่  </th>
                                    <th> รหัส</th>
                                    <th>พนักงานที่เกี่ยวข้อง</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?PHP 
                                    for ($i=0; $i < count($quotation); $i++) { 
                                ?>
                                    <tr>
                                        <td style="text-align:left;"> <?PHP echo $i+1,' ',$quotation[$i]['customer_purchase_order_id'] ?></td>
                                        <td><?PHP echo $quotation[$i]['quotation_date']  ?> </td>
                                        <td>
                                            <a target="_blank" href="index.php?app=quotation&action=detail&id=<?PHP echo $quotation[$i]['quotation_id']  ?>">
                                                <?PHP echo $quotation[$i]['quotation_code']  ?>
                                            </a>
                                        </td>
                                        <td>  
                                            <?PHP echo $quotation[$i]['user_prefix']  ?>
                                            <?PHP echo $quotation[$i]['user_name']  ?>
                                            <?PHP echo $quotation[$i]['user_lastname']  ?>
                                        </td>
                                    </tr>

                                <?PHP 
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>


                    <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab" >
                        <table width="100%" class="table table-striped table-bordered table-hover dataTables-order " id="dataTables-order">
                            <thead>
                                <tr>
                                    <th  style="width:100px;" > วันที่  </th>
                                    <th> รหัส</th>
                                    <th>พนักงานที่เกี่ยวข้อง	</th>
                                    <th>พนักงานที่เกี่ยวข้อง	</th>
                                </tr>
                            </thead>
                            <?PHP 
                            // for ($i=0; $i <count($product) ; $i++) {   
                                // if ( $product[$i]['customer_purchase_order_code'] != null && $product[$i+1]['customer_purchase_order_code'] != $product[$i]['customer_purchase_order_code']) {
                                                              
                            ?>

                            <tbody>
                                <?PHP 
                                    for ($i=0; $i < count($quotation); $i++) { 
                                ?>
                                    <tr>
                                        <td style="text-align:left;"> <?PHP echo $i+1 ?></td>
                                        <td><?PHP echo $quotation[$i]['customer_purchase_order_date']  ?> </td>
                                        <td><?PHP echo $quotation[$i]['customer_purchase_order_code_gen']  ?></td>
                                        <td>  
                                            <?PHP echo $quotation[$i]['user_prefix']  ?>
                                            <?PHP echo $quotation[$i]['user_name']  ?>
                                            <?PHP echo $quotation[$i]['user_lastname']  ?>
                                        </td>
                                    </tr>

                                <?PHP 
                                    }
                                ?>
                            </tbody>
                            <?PHP
                            // }
                                // }
                                ?>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="seller-tab">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-invoice">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:64px;" >ลำดับ</th>
                                    <th class="text-center" style="width:100px;" >วันที่</th>
                                    <th class="text-center" >ใบกำกับภาษี</th>
                                    <th class="text-center" >พนักงานที่เกี่ยวข้อง</th>
                                    <th class="text-center" >มูลค่ารวม</th>
                                    <th class="text-center" >ภาษีมูลค่าเพิ่ม</th>
                                    <th class="text-center" >มูลค่าสุทธิ</th>
                                    <th class="text-center" style="width:48px;" ></th>
                                </tr>
                            </thead>
                            <tbody class="odd gradeX">
                            <?PHP 
                            for ($i=0; $i <count($invoice) ; $i++) {   
                                if ( $invoice[$i]['invoice_customer_code'] != null && $invoice[$i+1]['invoice_customer_code'] != $invoice[$i]['invoice_customer_code']) {
                                                              
                            ?>
                                <tr>
                                    <td style="text-align:left;" ><?PHP echo number_format($i+1);?></td>
                                    <td data-order="<?php echo $timestamp = strtotime(  $invoice[$i]['invoice_customer_date']  ) ?>" ><?PHP echo $invoice[$i]['invoice_customer_date']  ?> </td>
                                    <td> <?PHP echo $invoice[$i]['invoice_customer_code']  ?> </td>
                                    <td>  
                                        <?PHP echo $invoice[$i]['user_prefix']  ?>
                                        <?PHP echo $invoice[$i]['user_name']  ?>
                                        <?PHP echo $invoice[$i]['user_lastname']  ?> 
                                    </td>
                                    <td align="right">
                                        <?PHP echo number_format($invoice[$i]['invoice_customer_total_price']); ?>
                                    </td>
                                    <td align="right">
                                        <?PHP echo number_format($invoice[$i]['invoice_customer_vat_price']); ?>
                                    </td>
                                    <td align="right">
                                        <?PHP echo number_format($invoice[$i]['invoice_customer_net_price']); ?>
                                    </td>
                                    <td>
                                        <a target="_blank" href="print.php?app=invoice_customer&action=pdf&id=<?PHP echo $invoice[$i]['invoice_customer_id']; ?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                 </tr>
                            
                            <?PHP
                            }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>