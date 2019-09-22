<?php

require_once("BaseModel.php");

class MaintenancePurchaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }
 


    function runMaintenance(){

        $sql = "TRUNCATE TABLE tb_journal_purchase ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "TRUNCATE TABLE tb_journal_purchase_list ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        //ดึงหัวเอกสารการรับสินค้าเข้า
        $sql = "    SELECT * 
                    FROM tb_invoice_supplier 
                    LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
                    WHERE invoice_supplier_begin = '0' 
                    ORDER BY STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') , invoice_supplier_code_gen 
        ";
        $data = [];
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 

            for($i = 0 ; $i < count($data) ; $i++){
                // ดึงรายการรับสินค้าในเอกสารนั้น -----------------------------------------------------------------
                $sql = "SELECT tb_invoice_supplier_list.* ,  tb_product.* , stock_event 
                FROM tb_invoice_supplier_list 
                LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
                LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product.product_category_id 
                LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                GROUP BY invoice_supplier_list_id
                ORDER BY invoice_supplier_list_no ";
                $data_sub = []; 

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }

                /*
                if($data[$i]['invoice_supplier_id'] == 276){
                    echo "<pre>";
                    print_r($data_sub);
                    echo "</pre>";

                }
                */

               
                $journal_list = [];
               //คำนวนต้นทุนในกรณีเป็นบริษัทภายในประเทศ ----------------------------------------------------------
                if( $data[$i]['supplier_domestic'] == "ภายในประเทศ"){
                    $total = 0;
                    $vat_price = 0;
                    $net_price = 0;

                    //วนรอบอัพเดทรายการสินค้า ---------------------------------
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){
                        $data_sub[$i_sub]['invoice_supplier_list_price'] = round($data_sub[$i_sub]['invoice_supplier_list_price'],2);
                        $data_sub[$i_sub]['invoice_supplier_list_cost'] = $data_sub[$i_sub]['invoice_supplier_list_price'];
                        $data_sub[$i_sub]['invoice_supplier_list_total'] = round($data_sub[$i_sub]['invoice_supplier_list_qty'] * $data_sub[$i_sub]['invoice_supplier_list_price'],2);
                        $total += $data_sub[$i_sub]['invoice_supplier_list_total'];

                        $sql = " UPDATE tb_invoice_supplier_list 
                                SET product_id = '".$data_sub[$i_sub]['product_id']."', 
                                invoice_supplier_list_product_name = '".$data_sub[$i_sub]['invoice_supplier_list_product_name']."',  
                                invoice_supplier_list_product_detail = '".$data_sub[$i_sub]['invoice_supplier_list_product_detail']."', 
                                invoice_supplier_list_qty = '".$data_sub[$i_sub]['invoice_supplier_list_qty']."', 
                                invoice_supplier_list_price = '".$data_sub[$i_sub]['invoice_supplier_list_price']."', 
                                invoice_supplier_list_total = '".$data_sub[$i_sub]['invoice_supplier_list_total']."', 
                                invoice_supplier_list_remark = '".$data_sub[$i_sub]['invoice_supplier_list_remark']."', 
                                stock_group_id = '".$data_sub[$i_sub]['stock_group_id']."', 
                                invoice_supplier_list_cost = '".$data_sub[$i_sub]['invoice_supplier_list_cost']."', 
                                purchase_order_list_id = '".$data_sub[$i_sub]['purchase_order_list_id']."' 
                                WHERE invoice_supplier_list_id = '".$data_sub[$i_sub]['invoice_supplier_list_id']."' 
                        "; 

                        //echo "<B> ".$data[$i]['invoice_supplier_code_gen']."---->".($i_sub+1)."===>".$data_sub[$i_sub]['product_id']." </B> : ".$sql ."<br><br>";
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_id'] == $data_sub[$i_sub]['buy_account_id']){
                                $has_account = true;
                                $journal_list[$ii]['invoice_supplier_list_total'] += $data_sub[$i_sub]['invoice_supplier_list_total'];
                                break;
                            }
                        }

                        if($has_account == false){
                            $journal_list[] = array (
                                "account_id"=>$data_sub[$i_sub]['buy_account_id'], 
                                "invoice_supplier_list_total"=>$data_sub[$i_sub]['invoice_supplier_list_total'] 
                            ); 
                        } 

                    }
                

                    //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                    $vat_price = $total * $data[$i]['invoice_supplier_vat']/100;

                    $net_price = $total + $vat_price;

                    $data[$i]['invoice_supplier_cost_total'] = round($total,2);
                    $data[$i]['invoice_supplier_currency_total'] = round($total,2);
                    $data[$i]['invoice_supplier_total_price'] = round($total,2);
                    $data[$i]['invoice_supplier_vat_price'] = round($vat_price,2);
                    $data[$i]['invoice_supplier_net_price'] = round($net_price,2);

                    $sql = "    UPDATE tb_invoice_supplier SET 
                                invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."', 
                                supplier_id = '".$data[$i]['supplier_id']."', 
                                employee_id = '".$data[$i]['employee_id']."', 
                                invoice_supplier_code = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code'])."', 
                                invoice_supplier_code_gen = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code_gen'])."', 
                                invoice_supplier_cost_total = '".$data[$i]['invoice_supplier_cost_total']."', 
                                invoice_supplier_currency_total = '".$data[$i]['invoice_supplier_currency_total']."', 
                                invoice_supplier_total_price = '".$data[$i]['invoice_supplier_total_price']."', 
                                invoice_supplier_total_stock_price = '".$data[$i]['invoice_supplier_total_stock_price']."', 
                                invoice_supplier_currency_stock_total = '".$data[$i]['invoice_supplier_currency_stock_total']."', 
                                invoice_supplier_cost_stock_total = '".$data[$i]['invoice_supplier_cost_stock_total']."', 
                                invoice_supplier_vat = '".$data[$i]['invoice_supplier_vat']."', 
                                invoice_supplier_vat_price = '".$data[$i]['invoice_supplier_vat_price']."', 
                                invoice_supplier_net_price = '".$data[$i]['invoice_supplier_net_price']."', 
                                invoice_supplier_date = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date'])."', 
                                invoice_supplier_date_recieve = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date_recieve'])."', 
                                invoice_supplier_name = '".static::$db->real_escape_string($data[$i]['invoice_supplier_name'])."', 
                                invoice_supplier_address = '".static::$db->real_escape_string($data[$i]['invoice_supplier_address'])."', 
                                invoice_supplier_tax = '".static::$db->real_escape_string($data[$i]['invoice_supplier_tax'])."', 
                                invoice_supplier_branch = '".static::$db->real_escape_string($data[$i]['invoice_supplier_branch'])."', 
                                invoice_supplier_term = '".static::$db->real_escape_string($data[$i]['invoice_supplier_term'])."', 
                                invoice_supplier_due = '".static::$db->real_escape_string($data[$i]['invoice_supplier_due'])."',  
                                invoice_supplier_begin = '".$data[$i]['invoice_supplier_begin']."', 
                                import_duty = '".$data[$i]['import_duty']."', 
                                freight_in = '".$data[$i]['freight_in']."', 
                                vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                                vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                                invoice_supplier_total_price_non = '".$data[$i]['invoice_supplier_total_price_non']."', 
                                invoice_supplier_vat_price_non = '".$data[$i]['invoice_supplier_vat_price_non']."', 
                                invoice_supplier_total_non = '".$data[$i]['invoice_supplier_total_non']."', 
                                invoice_supplier_description = '".static::$db->real_escape_string($data[$i]['invoice_supplier_description'])."', 
                                invoice_supplier_remark = '".static::$db->real_escape_string($data[$i]['invoice_supplier_remark'])."', 
                                updateby = '".$data[$i]['updateby']."', 
                                lastupdate = '".$data[$i]['lastupdate']."' 
                                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                    ";
            

            
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //account setting id = 9 ภาษีซื้อ  --> [1154-00] ภาษีซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '9' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_vat_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_vat_purchase  = $row;
                        }
                        $result->close();
                    } 
                        
                    //account setting id = 26 ซื้อสินค้า --> [5130-01] ซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '26' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_purchase  = $row;
                        }
                        $result->close();
                    }  
                    $account_supplier = $data[$i]['account_id'];

                    $this->updateJournal($data[$i],$journal_list, $account_supplier, $account_vat_purchase['account_id'],$account_purchase['account_id']);

                }else{

                    $sql = " SELECT *  FROM tb_exchange_rate_baht 
                    LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
                    WHERE tb_exchange_rate_baht.currency_id = '".$data[$i]['currency_id'] ."' 
                    AND tb_exchange_rate_baht.exchange_rate_baht_date = '". $data[$i]['invoice_supplier_date_recieve'] ."'  
                    "; 

                    if($data[$i]['invoice_supplier_id'] == '173'){

                    }

                    
            
                    $sum = 0.0;
                    $total = 0.0; 
                    $freight_in = $data[$i]['freight_in'];
                    $import_duty = $data[$i]['import_duty'];
            
                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $exchange = mysqli_fetch_array($result,MYSQLI_ASSOC);
                        $result->close();
                        $exchange_rate = round($exchange['exchange_rate_baht_value'],5);

                        

                    }else{
                        $exchange_rate = 1;
                    }
 

                    
                    /************************ Calculate currency and  exchange rate  *************************/

                    $invoice_supplier_list_total_sum = 0;
                    $invoice_supplier_list_currency_total_sum = 0;
                    $invoice_supplier_list_stock_total_sum = 0;
                    $invoice_supplier_list_currency_stock_total_sum = 0;
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){ 


                        $data_sub[$i_sub]['invoice_supplier_list_currency_total'] = round($data_sub[$i_sub]['invoice_supplier_list_qty'] * $data_sub[$i_sub]['invoice_supplier_list_currency_price'],2);
                        
                        $data_sub[$i_sub]['invoice_supplier_list_total'] = round($data_sub[$i_sub]['invoice_supplier_list_currency_total'] * $exchange_rate,2);
                        $data_sub[$i_sub]['invoice_supplier_list_price'] = round($data_sub[$i_sub]['invoice_supplier_list_total'] / $data_sub[$i_sub]['invoice_supplier_list_qty'],2); 
                        $total += $data_sub[$i_sub]['invoice_supplier_list_total'];


                        $invoice_supplier_list_total_sum += $data_sub[$i_sub]['invoice_supplier_list_total'];
                        $invoice_supplier_list_currency_total_sum += $data_sub[$i_sub]['invoice_supplier_list_currency_total'];

                        if($data_sub[$i_sub]['stock_event'] == '1'){
                            $invoice_supplier_list_stock_total_sum += $data_sub[$i_sub]['invoice_supplier_list_total'];
                            $invoice_supplier_list_currency_stock_total_sum += $data_sub[$i_sub]['invoice_supplier_list_currency_total'];
                        }

                        


                        
                        $has_account = false;
                        for($ii = 0 ; $ii < count($journal_list); $ii++){
                            if($journal_list[$ii]['account_id'] == $data_sub[$i_sub]['buy_account_id']){
                                $has_account = true;
                                $journal_list[$ii]['invoice_supplier_list_total'] +=  $data_sub[$i_sub]['invoice_supplier_list_total'];
                                break;
                            }
                        }



                        if($has_account == false){
                            $journal_list[] = array (
                                "account_id"=>$data_sub[$i_sub]['buy_account_id'], 
                                "invoice_supplier_list_total"=> $data_sub[$i_sub]['invoice_supplier_list_total'] 
                            ); 
                        } 



                    }

                    /************************ End Calculate currency and  exchange rate  *************************/
  



                    /************************ Freight in   *************************/
                    $freight_in_amount =0;
                    $index_import = 0;
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){ 

                        if($data_sub[$i_sub]['stock_event'] == '1'){
                            $cost_price_f = round(($data_sub[$i_sub]['invoice_supplier_list_total'] / $invoice_supplier_list_stock_total_sum * $freight_in),2); 
   

                            if($freight_in -  $freight_in_amount > 0){
                                $index_import = $i_sub;
                                if ($i_sub + 1 == count($data_sub)) {
                                    $cost_price_f = $freight_in -  $freight_in_amount;
                                    
                                }else if ($cost_price_f >= $freight_in -  $freight_in_amount) {
                                    $cost_price_f = $freight_in -  $freight_in_amount;
                                } 
                                $freight_in_amount = $freight_in_amount + $cost_price_f;
                            }else {
                                $cost_price_f = 0;
                            }
                            $data_sub[$i_sub]['invoice_supplier_list_freight_in']=round($cost_price_f / $data_sub[$i_sub]['invoice_supplier_list_qty'],2);
                            $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] = round($cost_price_f,2);
                        }else{
                            $data_sub[$i_sub]['invoice_supplier_list_freight_in']=0;
                            $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] = 0;
                        }
                        
                    }


                    if($freight_in -  $freight_in_amount > 0){
                        $cost_price_f = $freight_in -  $freight_in_amount + $data_sub[$index_import]['invoice_supplier_list_freight_in']; 
                        $data_sub[$index_import]['invoice_supplier_list_freight_in']=round($cost_price_f / $data_sub[$index_import]['invoice_supplier_list_qty'],2);
                        $data_sub[$index_import]['invoice_supplier_list_freight_in_total'] = round($cost_price_f,2);
                    }

                    /************************ End Freight in   *************************/


                    
                                    
                    /************************ Import duty   *************************/
                    $use_total = 0;
                    $all_duty = $import_duty;
                    $use_duty = 0;
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){ 
                        
                        
                        if($data_sub[$i_sub]['invoice_supplier_list_fix_type'] == 'percent-fix'){
                            $data_sub[$i_sub]['invoice_supplier_list_fix_type'] =  'percent-fix';
                            $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] = round($data_sub[$i_sub]['invoice_supplier_list_duty']/100*$data_sub[$i_sub]['invoice_supplier_list_total'],2);
                            $data_sub[$i_sub]['invoice_supplier_list_cost_total'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] + $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] +$data_sub[$i_sub]['invoice_supplier_list_total'],2);

                            $data_sub[$i_sub]['invoice_supplier_list_import_duty'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] / $data_sub[$i_sub]['invoice_supplier_list_qty'],2);
                        }else if($data_sub[$i_sub]['invoice_supplier_list_fix_type'] == 'price-fix'){
                            $data_sub[$i_sub]['invoice_supplier_list_fix_type'] =  'price-fix';
                            $data_sub[$i_sub]['invoice_supplier_list_cost_total'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] + $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] +$data_sub[$i_sub]['invoice_supplier_list_total'],2);
                            $data_sub[$i_sub]['invoice_supplier_list_import_duty'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] / $data_sub[$i_sub]['invoice_supplier_list_qty'],2);
                            
                            $data_sub[$i_sub]['invoice_supplier_list_duty'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] / $data_sub[$i_sub]['invoice_supplier_list_total'] * 100,2);
                            
                        }else{ 
                            $use_total += $data_sub[$i_sub]['invoice_supplier_list_total'];
                            $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] = 0;
                            $data_sub[$i_sub]['invoice_supplier_list_duty'] = 0 ;
                            $data_sub[$i_sub]['invoice_supplier_list_import_duty'] = 0;
                        }
                        $use_duty += $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'];
                        //console.log('Sumation : ',sum);
                    }

                    // if($data[$i]['invoice_supplier_id'] == '173'){
                    //     echo "import_duty : ".$import_duty."<br>"; 
                        
                    // }
                    $all_duty = $all_duty - $use_duty;
                    $use_duty = 0;

                    // if($data[$i]['invoice_supplier_id'] == '173'){
                    //     echo "<pre>";
                    //     print_r($exchange);
                    //     echo "</pre>";
                    // }

                    
                    $index_duty = 0;
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){  
                        if($data_sub[$i_sub]['invoice_supplier_list_fix_type'] ==  'no-fix' || $data_sub[$i_sub]['invoice_supplier_list_fix_type'] == ''){
                            $data_sub[$i_sub]['invoice_supplier_list_fix_type'] =  'no-fix';
                            // if($data[$i]['invoice_supplier_id'] == '173'){
                            //     echo "all_duty : ".$all_duty."<br>"; 
                            //     echo "use_duty : ".$use_duty."<br>"; 
                            //     echo "all_duty - use_duty : ".($all_duty - $use_duty)."<br><br><br>"; 
                                
                            // }
                            if($all_duty - $use_duty > 0){
                                $index_duty = $i_sub;
                                $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] = round(($all_duty * $data_sub[$i_sub]['invoice_supplier_list_total'] / $use_total),2);

                                if ($all_duty - $use_duty < $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] || $i_sub+1 == count($data_sub)){
                                    $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] = $all_duty - $use_duty;
                                } 
                                $data_sub[$i_sub]['invoice_supplier_list_cost_total'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] + $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] +$data_sub[$i_sub]['invoice_supplier_list_total'],2);

                                $data_sub[$i_sub]['invoice_supplier_list_import_duty'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] / $data_sub[$i_sub]['invoice_supplier_list_qty'],2);
                            
                                $data_sub[$i_sub]['invoice_supplier_list_duty'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] / $data_sub[$i_sub]['invoice_supplier_list_total'] * 100,2);
                            
                                // if($data[$i]['invoice_supplier_id'] == '173'){
                                //     echo "invoice_supplier_list_import_duty_total : ".$data_sub[$i_sub]['invoice_supplier_list_import_duty_total']."<br>"; 
                                //     echo "invoice_supplier_list_import_duty : ".$data_sub[$i_sub]['invoice_supplier_list_import_duty']."<br>"; 
                                //     echo "invoice_supplier_list_duty : ".$data_sub[$i_sub]['invoice_supplier_list_duty']."<br><br><br>"; 
                                    
                                // }

                                $use_duty += $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'];
                            }else{
                                $data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] = 0;
                                $data_sub[$i_sub]['invoice_supplier_list_cost_total'] = round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'] + $data_sub[$i_sub]['invoice_supplier_list_freight_in_total'] +$data_sub[$i_sub]['invoice_supplier_list_total'],2);


                                $data_sub[$i_sub]['invoice_supplier_list_import_duty'] = 0;
                                $data_sub[$i_sub]['invoice_supplier_list_duty'] = 0;
                            }
                        }  
                    } 

                    if($all_duty - $use_duty > 0){
                        $duty = $all_duty - $use_duty + $data_sub[$index_duty]['invoice_supplier_list_import_duty']; 
                        $data_sub[$index_duty]['invoice_supplier_list_import_duty']=round($duty / $data_sub[$index_duty]['invoice_supplier_list_qty'],2);
                        $data_sub[$index_duty]['invoice_supplier_list_import_duty_total'] = round($duty,2);
                    }
                    /************************ End Import duty   *************************/



                    /************************ Cost total calculate   *************************/
                    $invoice_supplier_list_cost_total_sum = 0;
                    $invoice_supplier_list_cost_stock_total_sum = 0;
                    $val_import_duty = 0;
                    $val_freight_total = 0;
                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){  

                        $val_import_duty += round($data_sub[$i_sub]['invoice_supplier_list_import_duty_total'],2);



                        $val_freight_total += round($data_sub[$i_sub]['invoice_supplier_list_freight_in_total'],2);



                        $data_sub[$i_sub]['invoice_supplier_list_cost'] = round(round($data_sub[$i_sub]['invoice_supplier_list_cost_total'],2)/$data_sub[$i_sub]['invoice_supplier_list_qty'],2);
                        
                        
                        $invoice_supplier_list_cost_total_sum += round($data_sub[$i_sub]['invoice_supplier_list_cost_total'],2);
                        
                        if($data_sub[$i_sub]['stock_event'] == '1'){
                            $invoice_supplier_list_cost_stock_total_sum += round($data_sub[$i_sub]['invoice_supplier_list_cost_total'],2);

                        }

                    }
                    /************************ End Cost total calculate   *************************/


                    for($i_sub = 0 ; $i_sub < count($data_sub); $i_sub ++ ){ 

                        $sql = " UPDATE tb_invoice_supplier_list 
                                SET product_id = '".$data_sub[$i_sub]['product_id']."', 
                                invoice_supplier_list_product_name = '".$data_sub[$i_sub]['invoice_supplier_list_product_name']."',  
                                invoice_supplier_list_product_detail = '".$data_sub[$i_sub]['invoice_supplier_list_product_detail']."', 
                                invoice_supplier_list_qty = '".$data_sub[$i_sub]['invoice_supplier_list_qty']."', 
                                invoice_supplier_list_fix_type = '".$data_sub[$i_sub]['invoice_supplier_list_fix_type']."', 
                                invoice_supplier_list_duty = '".$data_sub[$i_sub]['invoice_supplier_list_duty']."', 
                                invoice_supplier_list_import_duty = '".$data_sub[$i_sub]['invoice_supplier_list_import_duty']."', 
                                invoice_supplier_list_import_duty_total = '".$data_sub[$i_sub]['invoice_supplier_list_import_duty_total']."', 
                                invoice_supplier_list_freight_in = '".$data_sub[$i_sub]['invoice_supplier_list_freight_in']."', 
                                invoice_supplier_list_freight_in_total = '".$data_sub[$i_sub]['invoice_supplier_list_freight_in_total']."', 
                                invoice_supplier_list_currency_price = '".$data_sub[$i_sub]['invoice_supplier_list_currency_price']."', 
                                invoice_supplier_list_currency_total = '".$data_sub[$i_sub]['invoice_supplier_list_currency_total']."', 
                                invoice_supplier_list_price = '".$data_sub[$i_sub]['invoice_supplier_list_price']."', 
                                invoice_supplier_list_total = '".$data_sub[$i_sub]['invoice_supplier_list_total']."', 
                                invoice_supplier_list_remark = '".$data_sub[$i_sub]['invoice_supplier_list_remark']."', 
                                stock_group_id = '".$data_sub[$i_sub]['stock_group_id']."', 
                                invoice_supplier_list_cost = '".$data_sub[$i_sub]['invoice_supplier_list_cost']."', 
                                invoice_supplier_list_cost_total = '".$data_sub[$i_sub]['invoice_supplier_list_cost_total']."', 
                                purchase_order_list_id = '".$data_sub[$i_sub]['purchase_order_list_id']."' 
                                WHERE invoice_supplier_list_id = '".$data_sub[$i_sub]['invoice_supplier_list_id']."' 
                        "; 
                        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    }




                    //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------
                    $vat_price = $total * $data[$i]['invoice_supplier_vat']/100;

                    $net_price = $total + $vat_price;




                    $data[$i]['invoice_supplier_cost_total'] = round($invoice_supplier_list_cost_total_sum,2);
                    $data[$i]['invoice_supplier_currency_total'] = round($invoice_supplier_list_currency_total_sum,2);
                    $data[$i]['invoice_supplier_total_price'] = round($total,2);


                    $data[$i]['invoice_supplier_cost_stock_total'] = round($invoice_supplier_list_cost_stock_total_sum,2);
                    $data[$i]['invoice_supplier_currency_stock_total'] = round($invoice_supplier_list_currency_stock_total_sum,2);
                    $data[$i]['invoice_supplier_total_stock_price'] = round($invoice_supplier_list_stock_total_sum,2);


                    $data[$i]['import_duty'] = round($val_import_duty,2);
                    $data[$i]['freight_in'] = round($val_freight_total,2);
                    $data[$i]['invoice_supplier_vat_price'] = round($vat_price,2);
                    $data[$i]['invoice_supplier_net_price'] = round($net_price,2);

                    $sql = "    UPDATE tb_invoice_supplier SET 
                                invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."', 
                                supplier_id = '".$data[$i]['supplier_id']."', 
                                employee_id = '".$data[$i]['employee_id']."', 
                                invoice_supplier_code = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code'])."', 
                                invoice_supplier_code_gen = '".static::$db->real_escape_string($data[$i]['invoice_supplier_code_gen'])."', 
                                invoice_supplier_cost_total = '".$data[$i]['invoice_supplier_cost_total']."', 
                                invoice_supplier_currency_total = '".$data[$i]['invoice_supplier_currency_total']."', 
                                invoice_supplier_total_price = '".$data[$i]['invoice_supplier_total_price']."', 
                                invoice_supplier_total_stock_price = '".$data[$i]['invoice_supplier_total_stock_price']."', 
                                invoice_supplier_currency_stock_total = '".$data[$i]['invoice_supplier_currency_stock_total']."', 
                                invoice_supplier_cost_stock_total = '".$data[$i]['invoice_supplier_cost_stock_total']."', 
                                invoice_supplier_vat = '".$data[$i]['invoice_supplier_vat']."', 
                                invoice_supplier_vat_price = '".$data[$i]['invoice_supplier_vat_price']."', 
                                invoice_supplier_net_price = '".$data[$i]['invoice_supplier_net_price']."', 
                                invoice_supplier_date = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date'])."', 
                                invoice_supplier_date_recieve = '".static::$db->real_escape_string($data[$i]['invoice_supplier_date_recieve'])."', 
                                invoice_supplier_name = '".static::$db->real_escape_string($data[$i]['invoice_supplier_name'])."', 
                                invoice_supplier_address = '".static::$db->real_escape_string($data[$i]['invoice_supplier_address'])."', 
                                invoice_supplier_tax = '".static::$db->real_escape_string($data[$i]['invoice_supplier_tax'])."', 
                                invoice_supplier_branch = '".static::$db->real_escape_string($data[$i]['invoice_supplier_branch'])."', 
                                invoice_supplier_term = '".static::$db->real_escape_string($data[$i]['invoice_supplier_term'])."', 
                                invoice_supplier_due = '".static::$db->real_escape_string($data[$i]['invoice_supplier_due'])."',  
                                invoice_supplier_begin = '".$data[$i]['invoice_supplier_begin']."', 
                                import_duty = '".$data[$i]['import_duty']."', 
                                freight_in = '".$data[$i]['freight_in']."', 
                                vat_section = '".static::$db->real_escape_string($data[$i]['vat_section'])."', 
                                vat_section_add = '".static::$db->real_escape_string($data[$i]['vat_section_add'])."', 
                                invoice_supplier_total_price_non = '".$data[$i]['invoice_supplier_total_price_non']."', 
                                invoice_supplier_vat_price_non = '".$data[$i]['invoice_supplier_vat_price_non']."', 
                                invoice_supplier_total_non = '".$data[$i]['invoice_supplier_total_non']."', 
                                invoice_supplier_description = '".static::$db->real_escape_string($data[$i]['invoice_supplier_description'])."', 
                                invoice_supplier_remark = '".static::$db->real_escape_string($data[$i]['invoice_supplier_remark'])."', 
                                updateby = '".$data[$i]['updateby']."', 
                                lastupdate = '".$data[$i]['lastupdate']."' 
                                WHERE invoice_supplier_id = '".$data[$i]['invoice_supplier_id']."' 
                    ";
            
                    //echo "<B> ".$data[$i]['invoice_supplier_code_gen']." </B> : ".$sql ."<br><br><br><br><br>";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    //account setting id = 9 ภาษีซื้อ  --> [1154-00] ภาษีซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '9' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_vat_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_vat_purchase  = $row;
                        }
                        $result->close();
                    } 
                        
                    //account setting id = 26 ซื้อสินค้า --> [5130-01] ซื้อ
                    $sql = " SELECT *
                    FROM tb_account_setting 
                    LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                    LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                    WHERE tb_account_setting.account_setting_id = '26' 
                    ";

                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        $account_purchase ;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $account_purchase  = $row;
                        }
                        $result->close();
                    }  
    
                    $account_supplier = $data[$i]['account_id'];
/*
                    if($data[$i]['invoice_supplier_id'] == 276){
                        echo "<pre>";
                        print_r($data_sub);
                        echo "</pre>";
    
                        echo "<pre>";
                        print_r($journal_list);
                        echo "</pre>";
    
                    }
*/
                    $this->updateJournal($data[$i],$journal_list, $account_supplier, $account_vat_purchase['account_id'],$account_purchase['account_id']);
                    

                }    
            }
        } 

    } 

    function updateJournal($data,$journal_list, $account_supplier, $account_vat_purchase,$account_purchase){

        // echo "<pre>";
        // print_r($journal_list);
        // echo "</pre>";

        //----------------------------- สร้างสมุดรายวันซื้อ ----------------------------------------  
        $journal_purchase_name = "ซื้อเชื่อจาก ".$data['invoice_supplier_name']." [".$data['invoice_supplier_code_gen']."] "; 

        $sql = " SELECT * 
        FROM tb_journal_purchase 
        WHERE invoice_supplier_id = '".$data['invoice_supplier_id']."' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $journal = $row;
            }
            $result->close();
        }


        if($journal['journal_purchase_id'] != ""){
            $journal_purchase_id = $journal['journal_purchase_id'];

            $sql = " UPDATE tb_journal_purchase SET 
            journal_purchase_code = '".$data['invoice_supplier_code_gen']."', 
            journal_purchase_date = '".$data['invoice_supplier_date_recieve']."', 
            journal_purchase_name = '".$journal_purchase_name."', 
            updateby = '".$data['updateby']."', 
            lastupdate = NOW() 
            WHERE journal_purchase_id = '".$journal_purchase_id."' 
            ";

            // echo $sql."<br><br>";
    
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            $sql = " DELETE FROM tb_journal_purchase_list WHERE journal_purchase_id = '$journal_purchase_id' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        }else{
            $sql = " INSERT INTO tb_journal_purchase (
                invoice_supplier_id,
                journal_purchase_code, 
                journal_purchase_date,
                journal_purchase_name,
                addby,
                adddate,
                updateby, 
                lastupdate) 
            VALUES ('".
            $data['invoice_supplier_id']."','".
            $data['invoice_supplier_code_gen']."','".
            $data['invoice_supplier_date_recieve']."','".
            $journal_purchase_name."','".
            $data['addby']."',".
            "NOW(),'".
            $data['addby'].
            "',NOW()); 
            ";
    
            // echo $sql."<br><br>";
    
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $journal_purchase_id = mysqli_insert_id(static::$db);
            }
        }

       



        //----------------------------- สิ้นสุด สร้างสมุดรายวันซื้อ ----------------------------------------

        if($journal_purchase_id != ""){ 

            //---------------------------- เพิ่มรายการเจ้าหนี้ --------------------------------------------
            $journal_purchase_list_debit = 0;
            $journal_purchase_list_credit = 0;

            if((float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                $journal_purchase_list_debit = (float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_purchase_list_credit = 0;
            }else{
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = (float)filter_var( $data['invoice_supplier_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } 

            $sql = " INSERT INTO tb_journal_purchase_list (
                journal_purchase_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_purchase_list_name,
                journal_purchase_list_debit,
                journal_purchase_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_purchase_id."',  
                '0', 
                '0', 
                '0', 
                '0', 
                '".$account_supplier."', 
                '".$journal_purchase_name."', 
                '".$journal_purchase_list_debit."',
                '".$journal_purchase_list_credit."',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            ";

            // echo $sql."<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            
            //---------------------------- สิ้นสุด เพิ่มรายการเจ้าหนี้ --------------------------------------------
            

            //---------------------------- เพิ่มรายการซื้อเชื่อ --------------------------------------------
            for($i = 0; $i < count($journal_list) ; $i++){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;
                
                if($journal_list[$i]['account_id'] == 0){
                    $account_id = $account_purchase;
                }else{
                    $account_id = $journal_list[$i]['account_id'];
                }
                



                if((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $journal_purchase_list_debit = round((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                    $journal_purchase_list_credit = 0;
                }else{
                    $journal_purchase_list_debit = 0;
                    $journal_purchase_list_credit = round((float)filter_var( $journal_list[$i]['invoice_supplier_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),2);
                } 

                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_purchase_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '".$account_id."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                // echo $sql."<br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการซื้อเชื่อ --------------------------------------------


            //---------------------------- เพิ่มรายการภาษีซื้อ --------------------------------------------
            if((float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) != 0.0){
                $journal_purchase_list_debit = 0;
                $journal_purchase_list_credit = 0;

                if((float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                    $journal_purchase_list_debit = 0;
                    $journal_purchase_list_credit = (float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }else{
                    $journal_purchase_list_debit = (float)filter_var( $data['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $journal_purchase_list_credit = 0;
                }


                $sql = " INSERT INTO tb_journal_purchase_list (
                    journal_purchase_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_purchase_list_name,
                    journal_purchase_list_debit,
                    journal_purchase_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_purchase_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '". $data['invoice_supplier_id']."', 
                    '".$account_vat_purchase."', 
                    '".$journal_purchase_name."', 
                    '".$journal_purchase_list_debit."',
                    '".$journal_purchase_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                // echo $sql."<br><br><hr>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการภาษีซื้อ --------------------------------------------

        }
    }
}
?>