            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?PHP echo $company['company_name_en']; ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

            <?PHP if($company['company_code'] != 'AR'){ ?>
                <li id="link_arno" style="display:none;">
                    <a href="javascript:;" onclick="switch_login('../../arno/admin/controllers/changeLogin.php','../../arno/admin/');" >Switch to arno</a>
                </li>
            <?PHP } ?>

            <?PHP if($company['company_code'] != 'PC'){ ?>
                <li id="link_partner_chips" style="display:none;">
                    <a href="javascript:;" onclick="switch_login('../../partner-chips/admin/controllers/changeLogin.php','../../partner-chips/admin/');" >Switch to partner chips</a>
                </li>
            <?PHP } ?>

            <?PHP if($company['company_code'] != 'BM'){ ?>
                <li id="link_best_machine" style="display:none;">
                    <a href="javascript:;" onclick="switch_login('../../best-machine/admin/controllers/changeLogin.php','../../best-machine/admin/');" >Switch to best machine</a>
                </li>
            <?PHP } ?>

            <?PHP if($company['company_code'] != 'TM'){ ?>
                <li id="link_tool_management" style="display:none;">
                    <a href="javascript:;" onclick="switch_login('../../tool-management/admin/controllers/changeLogin.php','../../tool-management/admin/');" >Switch to tool management</a>
                </li>
            <?PHP } ?>

            <?PHP 
            ?>

                <li>
                    <a href="?app=report_stock_07">
                        <i class="fa fa-retweet">
                        <?php if(count($num_report_stock_07) > 0){?>
                        <span class="alert">
                            <?php echo count($num_report_stock_07);?>
                        </span>
                        <?php } ?>
                        </i> 
                    </a>
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw">
                            <?php if(count($notifications_new) > 0){?>
                            <span class="alert">
                                <?php echo count($notifications_new);?>
                            </span>
                            <?php } ?>
                        </i> 
                        <i class="fa fa-caret-down"></i>
                        
                    </a>
                    <ul class="dropdown-menu dropdown-alerts scroll1">
                        
                        <?php 

                        for($i=0 ; $i < count($notifications) ;$i++){ ?>
                            <li <?php if($notifications[$i]['notification_seen_date'] == ""){ ?>class="notify-active"<?php }else{ ?> class="notify" <?php } ?> >
                                <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" >
                                    <div>
                                    <?php if($notifications[$i]['notification_type'] =='Purchase Request'){ ?><i class="fa fa-comments fa-fw fa-notify"></i> <?php }
                                        else if ($notifications[$i]['notification_type'] =='Purchase Order'){?><i class="fa fa-tasks fa-fw fa-notify"></i> <?php }
                                        else if ($notifications[$i]['notification_type'] =='Customer Order'){?><i class="fa fa-cart-plus fa-fw fa-notify"></i> <?php }
                                        else {?><i class="fa fa-support fa-fw fa-notify"></i> <?php }
                                        ?>
                                        
                                        <?php echo $notifications[$i]['notification_detail'];?> 
                                        <div class=" text-muted small"><?php echo $notifications[$i]['notification_date'];?></div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php
                            // if($i == 10){break;}
                        } 
                
                        ?>
                        <li class="sticky-bot">
                            <a class="see_all" href="index.php?app=notification">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                                <i class="fa fa-angle-right"></i>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="index.php?app=user_profile"><i class="fa fa-user fa-fw"></i><?php echo $user_admin['user_name'];?> <?php echo $user_admin['user_lastname'];?></a>
                        </li>
                        <li><a href="index.php?app=user_profile"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->



            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                 
                            </div>
                            <!-- /input-group -->
                        </li>
                    <? 



                    //license_admin_page
                    if($license_admin_page == "High" || $license_admin_page == "Medium" || $license_admin_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                   $_GET["app"]=='employee'
                                || $_GET["app"]=='supplier'
                                || $_GET["app"]=='customer'
                                || $_GET["app"]=='product' 
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                 ระบบพื้นฐาน <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse"> 
                                <li>
                                    <a href="?app=setting" <?PHP if($_GET['app'] == "setting"){?> class="active" <?PHP } ?> ><i class="fa fa-gears" aria-hidden="true"></i> ตั้งค่าระบบ (System Setting)</a>
                                </li>
                                <li>
                                    <a href="?app=employee" <?PHP if($_GET['app'] == "employee"){?> class="active" <?PHP } ?> ><i class="fa fa-user" aria-hidden="true"></i> พนักงาน (Employee)</a>
                                </li>
                                <li>
                                    <a href="?app=supplier" <?PHP if($_GET['app'] == "supplier"){?> class="active" <?PHP } ?> ><i class="fa fa-building-o" aria-hidden="true"></i> ผู้ขาย (Supplier)</a>
                                </li>
                                <li>
                                    <a href="?app=customer" <?PHP if($_GET['app'] == "customer"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ลูกค้า (Customer)</a>
                                </li>
                                <li>
                                    <a href="?app=product" <?PHP if($_GET['app'] == "product"){?> class="active" <?PHP } ?> ><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> สินค้า (Product)</a>
                                </li>
                                
                            </ul>
                        </li>
                    <? 
                    }


                    
                    //Approve 
                    if($license_sale_employee_page == "High" || $license_account_page == "High" || $license_manager_page == "High" ){
                        ?>
                            <li
                            <?PHP 
                                if(
                                    $_GET["app"]=='approve_setting' || 
                                    $_GET["app"]=='approve_purchase_request' || 
                                    $_GET["app"]=='approve_purchase_order' || 
                                    $_GET["app"]=='approve_request_test' || 
                                    $_GET["app"]=='approve_customer' 
                                ){
                                    echo ' class="active" ';
                                }
                            ?> 
                            >
                                <a href="#" class="nav-title">
                                ระบบการอนุมัติ 
                                <?PHP if($all_approve_number > 0){ ?>
                                    <span class="menu-alert"> <?PHP echo number_format($all_approve_number);?> </span>
                                <?PHP } ?> 
                                
                                <span class="glyphicon arrow"></span>
                                </a>
    
                                <ul class="collapse"> 
                                    <?PHP if($license_manager_page == "High" ){ ?>
                                    <li>
                                        <a href="?app=approve_setting" <?PHP if($_GET['app'] == "approve_setting"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ตั้งค่าการอนุมัติ</a>
                                    </li>
                                    <?PHP }?>
                                    <li>
                                        <a href="?app=approve_purchase_request" <?PHP if($_GET['app'] == "approve_purchase_request"){?> class="active" <?PHP } ?> >
                                            <i class="fa  fa-file-o" aria-hidden="true"></i> ใบร้องขอสั่งซื้อสินค้า 
                                            <?PHP if($approve_purchase_request_number > 0){ ?>
                                            <span class="menu-alert"> <?PHP echo number_format($approve_purchase_request_number);?> </span>
                                            <?PHP } ?> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?app=approve_purchase_order&supplier_domestic=ภายในประเทศ" <?PHP if($_GET['app'] == "approve_purchase_order" && $_GET['supplier_domestic'] == "ภายในประเทศ"){?> class="active" <?PHP } ?> >
                                            <i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อสินค้า [ภายในประเทศ]
                                            <?PHP if($approve_purchase_order_in_number > 0){ ?>
                                            <span class="menu-alert"> <?PHP echo number_format($approve_purchase_order_in_number);?> </span> 
                                            <?PHP } ?> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?app=approve_purchase_order&supplier_domestic=ภายนอกประเทศ" <?PHP if($_GET['app'] == "approve_purchase_order" && $_GET['supplier_domestic'] == "ภายนอกประเทศ"){?> class="active" <?PHP } ?> >
                                            <i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อสินค้า [ภายนอกประเทศ]
                                            <?PHP if($approve_purchase_order_out_number > 0){ ?>
                                            <span class="menu-alert"> <?PHP echo number_format($approve_purchase_order_out_number);?> </span> 
                                            <?PHP } ?> 
                                        </a>
                                    </li>
                                    <li>
                                        <a href="?app=approve_request_test"  <?PHP if($_GET['app'] == "approve_request_test"){?> class="active" <?PHP } ?>    >
                                            <i class="fa  fa-file-o" aria-hidden="true"></i> ใบร้องขอสินค้าทดสอบ 
                                            <?PHP if($approve_request_test_number > 0){ ?>
                                            <span class="menu-alert"> <?PHP echo number_format($approve_request_test_number);?> </span> 
                                            <?PHP } ?> 
                                        </a>
                                    </li>  
                                    <li>
                                        <a href="?app=approve_customer"  <?PHP if($_GET['app'] == "approve_customer"){?> class="active" <?PHP } ?>    >
                                            <i class="fa  fa-file-o" aria-hidden="true"></i> ลูกค้าใหม่ 
                                            <?PHP if($approve_customer_number > 0){ ?>
                                            <span class="menu-alert"> <?PHP echo number_format($approve_customer_number);?> </span>
                                            <?PHP } ?> 
                                        </a>

                                    </li>
                                </ul>
                            </li>
                        <? 
                        }


                    //license_sale_employee_page
                    if($license_manager_page == "High" || $license_sale_employee_page == "High" || $license_sale_employee_page == "Medium" || $license_sale_employee_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='sale_benefit_setting' || 
                                $_GET["app"]=='sale_employee' || 
                                $_GET["app"]=='my_customer' || 
                                $_GET["app"]=='product_special' || 
                                $_GET["app"]=='price_list'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบพนักงานขาย <span class="glyphicon arrow"></span>
                            </a>

                            <ul class="collapse">    
                            <?PHP if($license_manager_page == "High" ){ ?>
                                <li>
                                    <a href="?app=sale_benefit_setting" <?PHP if($_GET['app'] == "sale_benefit_setting"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ตั้งค่าคอมมิสชั่น</a>
                                </li>
                            <?PHP } ?>
                                <li>
                                    <a href="?app=sale_employee" <?PHP if($_GET['app'] == "sale_employee"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายชื่อพนักงานขาย</a>
                                </li>
                                
                                <li>
                                    <a href="?app=my_customer" <?PHP if($_GET['app'] == "my_customer"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ลูกค้าของฉัน</a>
                                </li>
                                <li>
                                    <a href="?app=price_list"  <?PHP if($_GET['app'] == "price_list"){?> class="active" <?PHP } ?>    ><i class="fa  fa-file-o" aria-hidden="true"></i> ราคาสินค้า</a>
                                </li>   
                                <li>
                                    <a href="?app=product_special" <?PHP if($_GET['app'] == "product_special"){?> class="active" <?PHP } ?> ><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> สินค้าพิเศษ</a>
                                </li>
                            </ul>
                        </li>
                    <? 
                    }

                    //	license_request_page
                    if($license_request_page == "High" || $license_request_page == "Medium" || $license_request_page == "Low" ){
                    ?>

                        <li 
                        <?PHP 
                            if($_GET["app"]=='request_test'){
                                echo ' class="active" ';
                            }elseif($_GET["app"]=='request_standard'){
                                echo ' class="active" ';
                            }else if($_GET["app"]=='request_special'){
                                echo ' class="active" ';
                            }else if($_GET["app"]=='request_regrind'){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบสั่งสินค้าทดลอง <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">
                                <?PHP if($license_request_page == "High" || $license_request_page == "Medium"){ ?>
                                <li>
                                    <a href="?app=request_test" <?PHP if($_GET['app'] == "request_test"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งสินค้าทดลอง (Request Test)</a>
                                </li>
                                <?PHP } ?>


                                <li>
                                    <a href="?app=request_standard" <?PHP if($_GET['app'] == "request_standard"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้ามาตรฐาน (Standard Tool)</a>
                                </li>
                                <!-- <li>
                                    <a href="?app=request_special" <?PHP //if($_GET['app'] == "request_special"){?> class="active" <?PHP //} ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้าพิเศษ (Special Tool)</a>
                                </li>
                                <li>
                                    <a href="?app=request_regrind" <?PHP //if($_GET['app'] == "request_regrind"){?> class="active" <?PHP //} ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้ารีกายด์ (Regrind Tool)</a>
                                </li> -->
                               


                            </ul>
                        </li>

                    <? 
                    }


                    //	license_delivery_note_page
                    if($license_delivery_note_page == "High" || $license_delivery_note_page == "Medium" || $license_delivery_note_page == "Low" ){
                    ?>


                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='delivery_note_supplier'
                                || $_GET["app"]=='delivery_note_customer'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบใบยืม <span class="glyphicon arrow"></span>
                            </a>

                            <ul class="collapse" > 
                            
                            <li
                            <?PHP 
                                if(
                                    $_GET["app"]=='delivery_note_supplier'
                                    || $_GET["app"]=='delivery_note_customer'
                                ){
                                    echo ' class="active" ';
                                }
                            ?> 
                            >

                                <a href="#" >
                                    <i  aria-hidden="true"></i> ออกใบยืมสินค้า <span class="glyphicon arrow"></span>
                                </a>

                                <ul class="collapse" > 

                                    <li>
                                        <a href="?app=delivery_note_supplier_send" <?PHP if($_GET['app'] == "delivery_note_supplier_send"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ผู้ขาย (Supplier SSDN)</a>
                                    </li>
                                    <li>
                                        <a href="?app=delivery_note_customer" <?PHP if($_GET['app'] == "delivery_note_customer"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ลูกค้า (Customer CDN)</a>
                                    </li>

                                    </ul>
                                    <li
                            <?PHP 
                                if(
                                    $_GET["app"]=='delivery_note_supplier_send'
                                    || $_GET["app"]=='delivery_note_customer'
                                ){
                                    echo ' class="active" ';
                                }
                            ?> 
                            >

                                <a href="#" >
                                    <i  aria-hidden="true"></i> รับใบยืมสินค้า <span class="glyphicon arrow"></span>
                                </a>

                                <ul class="collapse" > 

                                    <li>
                                        <a href="?app=delivery_note_supplier" <?PHP if($_GET['app'] == "delivery_note_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ผู้ขาย (Supplier SDN)</a>
                                    </li>
                                    <li>
                                        <a href="?app=delivery_note_customer_receive" <?PHP if($_GET['app'] == "delivery_note_customer_receive"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ลูกค้า (Customer DNCR)</a>
                                    </li>

                                    </ul>
                                </ul>
                            </li>

                        </li>
                    <? 
                    }


                    //license_regrind_page
                    if($license_regrind_page == "High" || $license_regrind_page == "Medium" || $license_regrind_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='regrind_supplier'
                                || $_GET["app"]=='regrind_supplier_receive' 
                                || $_GET["app"]=='regrind_list' 
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบรีกายร์สินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" > 
                                <li>
                                    <a href="?app=regrind_list" <?PHP if($_GET['app'] == "regrind_list"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายการรีกายร์สินค้า (Regrind List)</a>
                                </li>
                                <li>
                                    <a href="?app=regrind_supplier" <?PHP if($_GET['app'] == "regrind_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบส่งรีกายร์สินค้า (Send Regrind)</a>
                                </li>
                                <li>
                                    <a href="?app=regrind_supplier_receive" <?PHP if($_GET['app'] == "regrind_supplier_receive"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับรีกายร์สินค้า (Receive Regrind)</a>
                                </li>
                            </ul>
                        </li>
                    <? 
                    }


                    //	license_purchase_page
                    if($license_purchase_page == "High" || $license_purchase_page == "Medium" || $license_purchase_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                  
                                   $_GET["app"]=='purchase_request'
                                || $_GET["app"]=='purchase_order'
                                || $_GET["app"]=='invoice_supplier'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบจัดซื้อ <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" >  
                               
                                <li>
                                    <a href="?app=quotation_supplier" <?PHP if($_GET['app'] == "quotation_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเสนอราคาผู้ขาย (QS)</a>
                                </li>

                                <li>
                                    <a href="?app=purchase_request" <?PHP if($_GET['app'] == "purchase_request"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ร้องขอสั่งซื้อสินค้า (PR)</a>
                                </li>
                                
                                <?PHP if($license_purchase_page == "High" || $license_purchase_page == "Medium"){ ?>
                                <li>
                                    <a href="?app=purchase_order&supplier_domestic=ภายในประเทศ" <?PHP if($_GET['app'] == "purchase_order" && $_GET['supplier_domestic'] == "ภายในประเทศ"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อ (PO) ภายในประเทศ</a>
                                </li>
                                <li>
                                    <a href="?app=purchase_order&supplier_domestic=ภายนอกประเทศ" <?PHP if($_GET['app'] == "purchase_order"  && $_GET['supplier_domestic'] == "ภายนอกประเทศ"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อ (PO) ภายนอกประเทศ</a>
                                </li>
                                <li>
                                    <a href="?app=invoice_supplier_tmp" <?PHP if($_GET['app'] == "invoice_supplier_tmp" ){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รับใบกำกับภาษีชั่วคราว</a>
                                </li>
                                
                                <li>
                                    <a href="?app=invoice_supplier&supplier_domestic=ภายในประเทศ" <?PHP if($_GET['app'] == "invoice_supplier"&& $_GET['supplier_domestic'] == "ภายในประเทศ"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับสินค้า ในประเทศ (Supplier Invoice) </a>
                                </li>
                                <li>
                                    <a href="?app=invoice_supplier&supplier_domestic=ภายนอกประเทศ" <?PHP if($_GET['app'] == "invoice_supplier"&& $_GET['supplier_domestic'] == "ภายนอกประเทศ"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับสินค้า นอกประเทศ (Supplier Invoice) </a>
                                </li>
                                
                                <li>
                                    <a href="?app=credit_note_supplier" <?PHP if($_GET['app'] == "credit_note_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบลดหนี้ซัพพลายเออร์ (Credit Note Suppler)</a>
                                </li>
                                <?PHP } ?>
                            </ul>
                        </li>
                    <? 
                    }


                    //license_sale_page
                    if($license_sale_page == "High" || $license_sale_page == "Medium" || $license_sale_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='quotation'
                                || $_GET["app"]=='customer_purchase_order'
                                || $_GET["app"]=='invoice_customer'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบขายสินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">   


                                <li>
                                    <a href="?app=quotation"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเสนอราคา (Quotation) </a>
                                </li>


                                <?PHP if($license_sale_page == "High" || $license_sale_page == "Medium" ){?>
                                <li>
                                    <a href="?app=customer_purchase_order" <?PHP if($_GET['app'] == "customer_purchase_order"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อลูกค้า (Customer PO) </a>
                                </li>
                                <li>
                                    <a href="?app=invoice_customer" <?PHP if($_GET['app'] == "invoice_customer"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบกำกับภาษี (Customer Invoice)</a>
                                </li>
                                
                                <?PHP } ?>


                            </ul>
                        </li>
                    <? 
                    }


                    //license_inventery_page
                    if($license_inventery_page == "High" || $license_inventery_page == "Medium" || $license_inventery_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='search_product'
                                || $_GET["app"]=='stock_type'
                                || $_GET["app"]=='stock_move'
                                || $_GET["app"]=='stock_change_product'
                                || $_GET["app"]=='stock_issue'
                                || $_GET["app"]=='maintenance_stock'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบคลังสินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">    

                                <li>
                                    <a href="?app=search_product" <?PHP if($_GET['app'] == "search_product"){?> class="active" <?PHP } ?> ><i class="fa fa-search fa-fw" aria-hidden="true"></i> ค้นหาสินค้า (Search product) </a>
                                </li>

                                <li>
                                    <a href="?app=stock_type" <?PHP if($_GET['app'] == "stock_type"){?> class="active" <?PHP } ?> ><i class="fa fa-database fa-fw" aria-hidden="true"></i> คลังสินค้า (Stock) </a>
                                </li> 

                                <?PHP if($license_inventery_page == "High" || $license_inventery_page == "Medium" ){?>
                                <li>
                                    <a href="?app=stock_move" <?PHP if($_GET['app'] == "stock_move"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบโอนคลังสินค้า (Transfer Stock)</a>
                                </li>
                                
                                <li>
                                    <a href="?app=stock_product_set" <?PHP if($_GET['app'] == "stock_product_set"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรวมสินค้าแบบชุด (Product set)</a>
                                </li>
                               
                                <li>
                                    <a href="?app=stock_change_product" <?PHP if($_GET['app'] == "stock_change_product"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบย้ายสินค้า (Change Product)</a>
                                </li>
                                <li>
                                    <a href="?app=stock_issue" <?PHP if($_GET['app'] == "stock_issue"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบนำออกสินค้า (Issue Stock)</a>
                                </li>
                                <li>
                                    <a href="?app=maintenance_stock" <?PHP if($_GET['app'] == "maintenance_stock"){?> class="active" <?PHP } ?> ><i class="fa fa-refresh" aria-hidden="true"></i> ซ่อมแซมระบบคลังสินค้า </a>
                                </li>
                                <?PHP } ?>


                            </ul>
                        </li>
                    <? 
                    }


                    //license_account_page
                    if($license_account_page == "High" || $license_account_page == "Medium" || $license_account_page == "Low" ){
                    ?>

                        <li
                        <?PHP 
                             if(
                                $_GET["app"]=='account' || 
                                $_GET["app"]=='account_setting' || 
                                $_GET["app"]=='paper' ||
                                $_GET["app"]=='credit_note' || 
                                $_GET["app"]=='debit_note' || 
                                $_GET["app"]=='billing_note' || 
                                $_GET["app"]=='finance_debit' || 
                                $_GET["app"]=='finance_credit' || 
                                $_GET["app"]=='finance_debit_account' || 
                                $_GET["app"]=='finance_credit_account' || 
                                $_GET["app"]=='official_receipt'|| 
                                substr($_GET["app"],0,15) =='journal_special' ||
                                $_GET["app"] =='other_expense' ||
                                $_GET["app"] =='credit_purchasing' ||
                                $_GET["app"] =='journal_general' ||
                                substr($_GET["app"],0,15) =='journal_special' ||
                                substr($_GET["app"],0,7) =='summit_'||
                                substr($_GET["app"],0,4) =='bank'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบบัญชี <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">  
                                <li>
                                    <a href="?app=account"><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> ผังบัญชี (Account Stucture)</a>
                                </li>
                                <?PHP 
                                if($license_account_page == "High" ){
                                ?>
                                <li>
                                    <a href="?app=account_setting" <?PHP if($_GET['app'] == "account_setting"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดบัญชีที่ต้องลงรายวัน (Account Setting)</a>
                                </li>
                                <li>
                                    <a href="?app=paper" <?PHP if($_GET['app'] == "paper"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดเลขที่เอกสาร (Paper Setting)</a>
                                </li>
                                <li>
                                    <a href="?app=finance_debit_account" <?PHP if($_GET['app'] == "finance_debit_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดวิธีการรับชำระหนี้ (Received Setting)</a>
                                </li>
                                <li>
                                    <a href="?app=finance_credit_account" <?PHP if($_GET['app'] == "finance_credit_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดวิธีการจ่ายชำระหนี้ (Payment Setting)</a>
                                </li>
                                <?PHP } ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,7) =='summit_'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึกยอดยกมา <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=summit_dedit" <?PHP if($_GET['app'] == "summit_dedit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ลูกหนี้คงค้าง</a>
                                        </li> 
                                        <li>
                                            <a href="?app=summit_credit" <?PHP if($_GET['app'] == "summit_credit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เจ้าหนี้คงค้าง</a>
                                        </li> 
                                        <li>
                                            <a href="?app=summit_product" <?PHP if($_GET['app'] == "summit_product"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้า/วัตถุดิบ </a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_check_pre_receipt" <?PHP if($_GET['app'] == "summit_check_pre_receipt"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เช็ครับล่วงหน้า </a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_check_pre_pay" <?PHP if($_GET['app'] == "summit_check_pre_pay"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เช็คจ่ายล่วงหน้า</a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_account" <?PHP if($_GET['app'] == "summit_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ยอดบัญชี</a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                <?PHP 
                                    if(
                                        $_GET["app"]=='account' || 
                                        $_GET["app"]=='credit_note' || 
                                        $_GET["app"]=='debit_note' || 
                                        $_GET["app"]=='billing_note' || 
                                        $_GET["app"]=='official_receipt'|| 
                                        $_GET["app"]=='finance_debit' || 
                                        $_GET["app"]=='finance_credit' 
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> การเงิน <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=finance_debit" <?PHP if($_GET['app'] == "finance_debit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รับชำระหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=finance_credit" <?PHP if($_GET['app'] == "finance_credit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> จ่ายชำระหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=credit_note" <?PHP if($_GET['app'] == "credit_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบลดหนี้ลูกค้า (Credit Note Customer)</a>
                                        </li>
                                        <li>
                                            <a href="?app=debit_note" <?PHP if($_GET['app'] == "debit_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเพิ่มหนี้ (Debit Note)</a>
                                        </li>
                                        <li>
                                            <a href="?app=billing_note" <?PHP if($_GET['app'] == "billing_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบวางบิล (Billing Note)</a>
                                        </li>
                                        <?PHP /*>
                                        <li>
                                            <a href="?app=official_receipt" <?PHP if($_GET['app'] == "official_receipt"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเสร็จ (Official Receipt)</a>
                                        </li>
                                        <?PHP */?>
                                    </ul>
                                </li>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,4) =='bank'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-university" aria-hidden="true"></i> ธนาคาร <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=bank_check_in_deposit" <?PHP if($_GET['app'] == "bank_check_in_deposit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> บันทึกเช็คนำฝาก</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_in_pass" <?PHP if($_GET['app'] == "bank_check_in_pass"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> บันทึกเช็คผ่าน</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_in" <?PHP if($_GET['app'] == "bank_check_in"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ทะเบียนเช็ครับ</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_pay_pass" <?PHP if($_GET['app'] == "bank_check_pay_pass"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ผ่านเช็คจ่าย</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_pay" <?PHP if($_GET['app'] == "bank_check_pay"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ทะเบียนเช็คจ่าย</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_account" <?PHP if($_GET['app'] == "bank_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายละเอียดบัญชีเงินฝาก</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank" <?PHP if($_GET['app'] == "bank"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายละเอียดธนาคาร</a>
                                        </li> 
                                    </ul>
                                </li>
                                
<!--
                                <li>
                                    <a href="?app=other_expense" <?PHP if($_GET['app'] == "other_expense"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ค่าใช้จ่ายอื่นๆ</a>
                                </li>

                                <li>
                                    <a href="?app=credit_purchasing" <?PHP if($_GET['app'] == "credit_purchasing"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ซื้อเงินเชื่อ</a>
                                </li>
-->
                                <li>
                                    <a href="?app=journal_general" <?PHP if($_GET['app'] == "journal_general"){?> class="active" <?PHP } ?> ><i class="fa fa-book" aria-hidden="true"></i> สมุดรายวันทั่วไป</a>
                                </li>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,15) =='journal_special'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-book" aria-hidden="true"></i> สมุดรายวันเฉพาะ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=journal_special_01" <?PHP if($_GET['app'] == "journal_special_01"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันซื่อสินค้า</a>
                                        </li> 
                                        <li>
                                            <a href="?app=journal_special_02" <?PHP if($_GET['app'] == "journal_special_02"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันขายสินค้า</a>
                                        </li> 
                                        <li>
                                            <a href="?app=journal_special_03" <?PHP if($_GET['app'] == "journal_special_03"){?> class="active" <?PHP } ?>  ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันรับเงิน</a>
                                        </li>
                                        <li>
                                            <a href="?app=journal_special_04" <?PHP if($_GET['app'] == "journal_special_04"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันจ่ายเงิน</a>
                                        </li>
                                    <!--
                                        <li>
                                            <a href="?app=journal_special_05" <?PHP if($_GET['app'] == "journal_special_05"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันส่งคืนสินค้าและจำนวนที่ได้ลด</a>
                                        </li>
                                    -->
                                        <li>
                                            <a href="?app=journal_special_06" <?PHP if($_GET['app'] == "journal_special_06"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันรับคืนสินค้าและจำนวนที่ลดให้</a>
                                        </li>
                                    
                                    </ul>
                                </li>
                                <li>
                                    <a href="?app=maintenance" <?PHP if($_GET['app'] == "maintenance"){?> class="active" <?PHP } ?> ><i class="fa fa-refresh" aria-hidden="true"></i> ซ่อมแซมระบบ </a>
                                </li>
                                <li>
                                    <a href="?app=paper_lock" <?PHP if($_GET['app'] == "paper_lock"){?> class="active" <?PHP } ?> ><i class="fa fa-lock" aria-hidden="true"></i> ล็อกงวดบัญชี </a>
                                </li>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,5) =='asset'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-gears" aria-hidden="true"></i> รายการทรัพย์สิน <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=asset" <?PHP if($_GET['app'] == "asset"){?> class="active" <?PHP } ?> ><i class="fa  fa-list" aria-hidden="true"></i> รายการทรัพย์สิน</a>
                                        </li> 
                                        <li>
                                            <a href="?app=asset_category" <?PHP if($_GET['app'] == "asset_category"){?> class="active" <?PHP } ?> ><i class="fa  fa-gear" aria-hidden="true"></i> หมวดหมู่ทรัพย์</a>
                                        </li> 
                                        <li>
                                            <a href="?app=asset_account_group" <?PHP if($_GET['app'] == "asset_account_group"){?> class="active" <?PHP } ?> ><i class="fa  fa-th-list" aria-hidden="true"></i> กลุ่มบัญชีทรัพย์</a>
                                        </li> 
                                        <li>
                                            <a href="?app=asset_department" <?PHP if($_GET['app'] == "asset_department"){?> class="active" <?PHP } ?>  ><i class="fa  fa-gears" aria-hidden="true"></i> แผนก</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    <? 
                    }


                    //license_account_page
                    if($license_report_page == "High" || $license_report_page == "Medium" || $license_report_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                             if(
                                substr($_GET["app"],0,13) =='report_debtor'  || 
                                substr($_GET["app"],0,15) =='report_creditor' ||
                                substr($_GET["app"],0,10) =='report_tax' ||
                                substr($_GET["app"],0,12) =='report_stock'||
                                substr($_GET["app"],0,14) =='report_account'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบรายงาน 
                                <?php if(count($num_report_stock_07) > 0){?>
                                <span class="menu-alert">
                                    <?php echo count($num_report_stock_07);?>
                                </span>
                                <?php } ?>
                                <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">


                        <?PHP if($license_report_page == "Medium" || $license_report_page == "High" ){ ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,13) =='report_debtor'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> ลูกหนี้ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" > 
                                        <li>
                                            <a href="?app=report_debtor_01" <?PHP if($_GET['app'] == "report_debtor_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบกำกับภาษี</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_debtor_02" <?PHP if($_GET['app'] == "report_debtor_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบวางบิล</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_03" <?PHP if($_GET['app'] == "report_debtor_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบลดหนี้/รับคืน</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_12" <?PHP if($_GET['app'] == "report_debtor_12"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบเพิ่มหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_04" <?PHP if($_GET['app'] == "report_debtor_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รับชำระหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_05" <?PHP if($_GET['app'] == "report_debtor_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ลูกหนี้คงค้าง</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_06" <?PHP if($_GET['app'] == "report_debtor_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สถานะลูกหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_07" <?PHP if($_GET['app'] == "report_debtor_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> วิเคราะห์อายุลูกหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_08" <?PHP if($_GET['app'] == "report_debtor_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายการเคลื่อนไหวลูกหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_debtor_09" <?PHP if($_GET['app'] == "report_debtor_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดลูกค้า</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_10" <?PHP if($_GET['app'] == "report_debtor_10"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบเสนอราคา</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_11" <?PHP if($_GET['app'] == "report_debtor_11"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> เอกสารที่ยังไม่วางบิล</a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            <?PHP } ?>

                            <?PHP if($license_report_page == "Medium" ||  $license_report_page == "High" ){ ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,15) =='report_creditor'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> เจ้าหนี้ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" > 
                                        <li>
                                            <a href="?app=report_creditor_01" <?PHP if($_GET['app'] == "report_creditor_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบสั่งซื้อ</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_creditor_02" <?PHP if($_GET['app'] == "report_creditor_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ซื้อเงินเชื่อ(ใบรับสินค้า)</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_creditor_03" <?PHP if($_GET['app'] == "report_creditor_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบราคาต้นทุน (ภายนอกประเทศ)</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_creditor_10" <?PHP if($_GET['app'] == "report_creditor_10"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ใบราคาต้นทุน (ภายในประเทศ)</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_creditor_04" <?PHP if($_GET['app'] == "report_creditor_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> การจ่ายชำระหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_creditor_05" <?PHP if($_GET['app'] == "report_creditor_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> เจ้าหนี้คงค้าง</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_creditor_06" <?PHP if($_GET['app'] == "report_creditor_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สถานะเจ้าหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_creditor_07" <?PHP if($_GET['app'] == "report_creditor_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> วิเคราะห์อายุเจ้าหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_creditor_08" <?PHP if($_GET['app'] == "report_creditor_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายการเคลื่อนไหวเจ้าหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_creditor_09" <?PHP if($_GET['app'] == "report_creditor_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดผู้จำหน่าย</a>
                                        </li>
                                    </ul>
                                </li>   
                            <?PHP } ?>
                            
                            <?PHP if($license_report_page == "High" ){ ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,10) =='report_tax'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> ภาษี <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=report_tax_01" <?PHP if($_GET['app'] == "report_tax_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ภาษีซื้อ </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_tax_02" <?PHP if($_GET['app'] == "report_tax_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ภาษีขาย </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_tax_03" <?PHP if($_GET['app'] == "report_tax_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> มูลค่าฐานภาษี</a>
                                        </li>
                                       
                                    </ul>
                                </li>
                                <?PHP } ?>
                            
                                <?PHP if($license_report_page == "Low" || $license_report_page == "Medium" || $license_report_page == "High"){ ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,12) =='report_stock'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> สินค้าคงคลัง 
                                        <?php if(count($num_report_stock_07) > 0){?>
                                        <span class="menu-alert">
                                            <?php echo count($num_report_stock_07);?>
                                        </span>
                                        <?php } ?>
                                        <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=report_stock_01" <?PHP if($_GET['app'] == "report_stock_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าและวัตถุดิบ </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_02" <?PHP if($_GET['app'] == "report_stock_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าคงเหลือ </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_03" <?PHP if($_GET['app'] == "report_stock_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สรุปยอดเคลื่อนไหวสินค้า </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_04" <?PHP if($_GET['app'] == "report_stock_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดสินค้า </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_05" <?PHP if($_GET['app'] == "report_stock_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานราคาขายสินค้า </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_06" <?PHP if($_GET['app'] == "report_stock_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานรายการประจำวัน </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_07" <?PHP if($_GET['app'] == "report_stock_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> 
                                                จุดสั่งซื้อ 
                                                <?php if(count($num_report_stock_07) > 0){?>
                                                <span class="menu-alert">
                                                    <?php echo count($num_report_stock_07);?>
                                                </span>
                                                <?php } ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_08" <?PHP if($_GET['app'] == "report_stock_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าที่ไม่เคลื่อนไหว</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_09" <?PHP if($_GET['app'] == "report_stock_09"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าไม่มีการขาย</a>
                                        </li>  
                                        <li>
                                            <a href="?app=report_stock_10" <?PHP if($_GET['app'] == "report_stock_10"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สินค้าเคลื่อนไหวที่มีปัญหา</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_stock_11" <?PHP if($_GET['app'] == "report_stock_11"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> มูลค่าตามคลังสินค้า</a>
                                        </li>
                                    </ul>
                                </li>
                            <?PHP } ?>
                            
                            <?PHP if($license_report_page == "High"  ){ ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,14) =='report_account'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> บัญชี <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=report_account_01" <?PHP if($_GET['app'] == "report_account_01"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ผังบัญชี </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_account_02" <?PHP if($_GET['app'] == "report_account_02"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> ยอดเคลื่อนไหว </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_account_03" <?PHP if($_GET['app'] == "report_account_03"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> งบเเสดงสถานะการเงิน </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_account_04" <?PHP if($_GET['app'] == "report_account_04"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> สมุดรายวัน </a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_account_05" <?PHP if($_GET['app'] == "report_account_05"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> งบทดลอง </a>
                                        </li> 
                                        <li>
                                            <a href="?app=report_account_06" <?PHP if($_GET['app'] == "report_account_06"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> บัญชีแยกประเภท </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_account_07" <?PHP if($_GET['app'] == "report_account_07"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานเช็คจ่ายคงเหลือ </a>
                                        </li>
                                        <li>
                                            <a href="?app=report_account_08" <?PHP if($_GET['app'] == "report_account_08"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานเช็ครับ </a>
                                        </li>
                                      
                                        <li>
                                            <a href="?app=report_account_10" <?PHP if($_GET['app'] == "report_account_10"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> รายงานงบกำไรขาดทุน </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                <?PHP } ?>
                            </ul>
                        </li>

                    <? 
                    }

                    ?>

                        <li 
                        <?PHP 
                            if($_GET["app"]=='job'){
                                echo ' class="active" ';
                            }
                        ?>  
                        <?php  if($license_regrind_page == "High" || $license_regrind_page == "Medium" || $license_regrind_page == "Low" ){ ?>
                                <li
                                <?PHP 
                                    if(
                                        $_GET["app"]=='customer_service_report'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" class="nav-title">
                                    ระบบรายงานการบริการ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" > 
                                        <li>
                                            <a href="?app=customer_service_report" <?PHP if($_GET['app'] == "customer_service_report"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายการรายงานการบริการ (CSR List)</a>
                                        </li>
                                    </ul>
                                </li>
                            <? 
                            }
                        ?>
                        <!-- /.nav-second-level -->
                    
 


                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>

