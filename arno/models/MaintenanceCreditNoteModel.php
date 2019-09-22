<?php

require_once("BaseModel.php"); 
class MaintenanceCreditNoteModel extends BaseModel{
 
    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
        mysqli_set_charset(static::$db,"utf8");
    }

    function runMaintenance(){
        //ดึงหัวเอกสารการรับสินค้าเข้า

        $sql = "TRUNCATE TABLE tb_journal_sale_return ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "TRUNCATE TABLE tb_journal_sale_return_list ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        $sql = "    SELECT * 
                    FROM tb_credit_note 
                    LEFT JOIN tb_customer ON tb_credit_note.customer_id = tb_customer.customer_id  
                    ORDER BY STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') , credit_note_code 
        ";
        $data = [];

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }

            $result->close(); 

            for($i = 0 ; $i < count($data) ; $i++){
                // ดึงรายการรับสินค้าในเอกสารนั้น -----------------------------------------------------------------
                $sql = "SELECT * 
                FROM tb_credit_note_list 
                LEFT JOIN tb_product ON tb_credit_note_list.product_id = tb_product.product_id  
                WHERE credit_note_id = '".$data[$i]['credit_note_id']."' 
                ORDER BY credit_note_list_id ";
                $data_sub = []; 

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub[] = $row;
                    }
                    $result->close(); 
                }
 

                $total = 0;
                $vat_price = 0;
                $net_price = 0;

                $journal_list = [];
                //วนรอบอัพเดทรายการสินค้า ---------------------------------
                for($i_sup = 0 ; $i_sup < count($data_sub); $i_sup ++ ){
                    $data_sub[$i_sup]['credit_note_list_price'] = round($data_sub[$i_sup]['credit_note_list_price'],2); 
                    $data_sub[$i_sup]['credit_note_list_total'] = round($data_sub[$i_sup]['credit_note_list_qty'] * $data_sub[$i_sup]['credit_note_list_price'],2);
                    $total += $data_sub[$i_sup]['credit_note_list_total'];

                    $sql = " UPDATE tb_credit_note_list 
                    SET product_id = '".$data_sub[$i_sup]['product_id']."', 
                    credit_note_list_product_name = '".$data_sub[$i_sup]['credit_note_list_product_name']."', 
                    credit_note_list_product_detail = '".$data_sub[$i_sup]['credit_note_list_product_detail']."',
                    credit_note_list_qty = '".$data_sub[$i_sup]['credit_note_list_qty']."',
                    credit_note_list_price = '".$data_sub[$i_sup]['credit_note_list_price']."', 
                    credit_note_list_total = '".$data_sub[$i_sup]['credit_note_list_total']."',
                    credit_note_list_remark = '".$data_sub[$i_sup]['credit_note_list_remark']."', 
                    invoice_customer_list_id = '".$data_sub[$i_sup]['invoice_customer_list_id']."',
                    stock_group_id = '".$data_sub[$i_sup]['stock_group_id']."'
                    WHERE credit_note_list_id = '".$data_sub[$i_sup]['credit_note_list_id']."'
                    ";

                    //echo "<B> ".$data[$i]['credit_note_code']."---->".($i_sup+1)."===>".$data_sub[$i_sup]['product_id']." </B> : ".$sql ."<br><br>";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 


                    $has_account = false;
                    for($ii = 0 ; $ii < count($journal_list); $ii++){
                        if($journal_list[$ii]['account_id'] == $data_sub[$i_sup]['sale_account_id']){
                            $has_account = true;
                            $journal_list[$ii]['credit_note_list_total'] += $data_sub[$i_sup]['credit_note_list_total'];
                            break;
                        }
                    }

                    if($has_account == false){
                        $journal_list[] = array (
                            "account_id"=>$data_sub[$i_sup]['sale_account_id'], 
                            "credit_note_list_total"=>$data_sub[$i_sup]['credit_note_list_total'] 
                        ); 
                    } 



                }
/*
                if($data[$i]['credit_note_id'] == 549){
                    echo "<pre>";
                    print_r($data_sub);
                    echo "</pre>";

                    echo "<pre>";
                    print_r($journal_list);
                    echo "</pre>";

                }
*/

                //อัพเดทหัวข้อเอกสารรับสินค้าเข้า ----------------------------------------------------------------------

                $total = 
                $vat_price = $total * $data[$i]['credit_note_vat']/100;

                $net_price = $total + $vat_price;

                $data[$i]['credit_note_total'] = round($data[$i]['credit_note_total_old'] - $total,2);
                $data[$i]['credit_note_total_price'] = round($total,2);
                $data[$i]['credit_note_vat_price'] = round($vat_price,2);
                $data[$i]['credit_note_net_price'] = round($net_price,2);

                $sql = " UPDATE tb_credit_note SET 
                        customer_id = '".$data[$i]['customer_id']."', 
                        employee_id = '".$data[$i]['employee_id']."', 
                        invoice_customer_id = '".$data[$i]['invoice_customer_id']."', 
                        credit_note_invoice_code = '".$data[$i]['credit_note_invoice_code']."', 
                        credit_note_type_id = '".$data[$i]['credit_note_type_id']."', 
                        credit_note_code = '".$data[$i]['credit_note_code']."', 
                        credit_note_total_old = '".$data[$i]['credit_note_total_old']."', 
                        credit_note_total = '".$data[$i]['credit_note_total']."', 
                        credit_note_total_price = '".$data[$i]['credit_note_total_price']."', 
                        credit_note_vat = '".$data[$i]['credit_note_vat']."', 
                        credit_note_vat_price = '".$data[$i]['credit_note_vat_price']."', 
                        credit_note_net_price = '".$data[$i]['credit_note_net_price']."', 
                        credit_note_date = '".$data[$i]['credit_note_date']."', 
                        credit_note_remark = '".$data[$i]['credit_note_remark']."', 
                        credit_note_name = '".$data[$i]['credit_note_name']."', 
                        credit_note_address = '".$data[$i]['credit_note_address']."', 
                        credit_note_tax = '".$data[$i]['credit_note_tax']."', 
                        credit_note_branch = '".$data[$i]['credit_note_branch']."', 
                        credit_note_term = '".$data[$i]['credit_note_term']."', 
                        credit_note_due = '".$data[$i]['credit_note_due']."', 
                        credit_note_due_day = '".$data[$i]['credit_note_due_day']."', 
                        credit_note_close = '".$data[$i]['credit_note_close']."', 
                        updateby = '".$data[$i]['updateby']."', 
                        lastupdate = '".$data[$i]['lastupdate']."' 
                        WHERE credit_note_id = '".$data[$i]['credit_note_id']."' 
                ";

                //echo "<B> ".$data[$i]['credit_note_code']." </B> : ".$sql ."<br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                //account setting id = 15 ภาษีขาย --> [2135-00] ภาษีขาย 
                $sql = " SELECT *
                FROM tb_account_setting 
                LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                WHERE tb_account_setting.account_setting_id = '15' 
                ";

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    $account_vat_sale ;
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $account_vat_sale  = $row;
                    }
                    $result->close();
                } 
                    
                //account setting id = 19 ขายเชื่อ --> [4100-01] รายได้-ขายอะไหล่ชิ้นส่วน
                $sql = " SELECT *
                FROM tb_account_setting 
                LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
                LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
                WHERE tb_account_setting.account_setting_id = '19' 
                ";

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    $account_sale ;
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $account_sale  = $row;
                    }
                    $result->close();
                }  
 
                $account_customer = $data[$i]['account_id'];

                $this->updateJournal($data[$i],$journal_list, $account_customer, $account_vat_sale['account_id'],$account_sale['account_id']);

            }
        }
    } 

    function updateJournal($data,$journal_list, $account_customer, $account_vat_sale,$account_sale){
        //----------------------------- สร้างสมุดรายวันขาย ----------------------------------------  
        $journal_sale_return_name = "ลดหนี้ให้ ".$data['credit_note_name']." [".$data['credit_note_code']."] "; 

        $sql = " SELECT * 
        FROM tb_journal_sale_return 
        WHERE credit_note_id = '".$data['credit_note_id']."' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $journal = $row;
            }
            $result->close();
        }


        if($journal['journal_sale_return_id'] != ""){
            $journal_sale_return_id = $journal['journal_sale_return_id'];

            $sql = " UPDATE tb_journal_sale_return SET 
            journal_sale_return_code = '".$data['credit_note_code']."', 
            journal_sale_return_date = '".$data['credit_note_date']."', 
            journal_sale_return_name = '".$journal_sale_return_name."', 
            updateby = '".$data['updateby']."', 
            lastupdate = NOW() 
            WHERE journal_sale_return_id = '".$journal_sale_return_id."' 
            ";

           // echo '<pre>'.$sql."</pre><br><br>";
    
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            $sql = " DELETE FROM tb_journal_sale_return_list WHERE journal_sale_return_id = '$journal_sale_return_id' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        }else{
            $sql = " INSERT INTO tb_journal_sale_return (
                credit_note_id,
                journal_sale_return_code, 
                journal_sale_return_date,
                journal_sale_return_name,
                addby,
                adddate,
                updateby, 
                lastupdate) 
            VALUES ('".
            $data['credit_note_id']."','".
            $data['credit_note_code']."','".
            $data['credit_note_date']."','".
            $journal_sale_return_name."','".
            $data['addby']."',".
            "NOW(),'".
            $data['addby'].
            "',NOW()); 
            ";
    
            //echo '<pre>'.$sql."</pre><br><br>";
    
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $journal_sale_return_id = mysqli_insert_id(static::$db);
            }
        }

       



        //----------------------------- สิ้นสุด สร้างสมุดรายวันขาย ----------------------------------------

        if($journal_sale_return_id != ""){ 

            //---------------------------- เพิ่มรายการลูกหนี้ --------------------------------------------
            $journal_sale_return_list_debit = 0;
            $journal_sale_return_list_credit = 0;

            if((float)filter_var( $data['credit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                $journal_sale_return_list_debit = 0;
                $journal_sale_return_list_credit = (float)filter_var( $data['credit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            }else{
                $journal_sale_return_list_debit = (float)filter_var( $data['credit_note_net_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $journal_sale_return_list_credit = 0;
            } 

            $sql = " INSERT INTO tb_journal_sale_return_list (
                journal_sale_return_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_sale_return_list_name,
                journal_sale_return_list_debit,
                journal_sale_return_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_sale_return_id."',  
                '0', 
                '0', 
                '0', 
                '0', 
                '".$account_customer."', 
                '".$journal_sale_return_name."', 
                '".$journal_sale_return_list_debit."',
                '".$journal_sale_return_list_credit."',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            ";

            //echo '<pre>'.$sql."</pre><br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            
            //---------------------------- สิ้นสุด เพิ่มรายการลูกหนี้ --------------------------------------------
            

            //---------------------------- เพิ่มรายการขายเชื่อ --------------------------------------------
            for($i = 0; $i < count($journal_list) ; $i++){
                $journal_sale_return_list_debit = 0;
                $journal_sale_return_list_credit = 0;
                
                if($journal_list[$i]['account_id'] == 0 ){
                    $account_id = $account_sale;
                }else{
                    $account_id = $journal_list[$i]['account_id'];
                }
                



                if((float)filter_var( $journal_list[$i]['credit_note_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) < 0){
                    $journal_sale_return_list_debit = 0;
                    $journal_sale_return_list_credit = (float)filter_var( $journal_list[$i]['credit_note_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                
                }else{
                    $journal_sale_return_list_debit = (float)filter_var( $journal_list[$i]['credit_note_list_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $journal_sale_return_list_credit = 0;
                } 

                $sql = " INSERT INTO tb_journal_sale_return_list (
                    journal_sale_return_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_sale_return_list_name,
                    journal_sale_return_list_debit,
                    journal_sale_return_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_sale_return_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '".$account_id."', 
                    '".$journal_sale_return_name."', 
                    '".$journal_sale_return_list_debit."',
                    '".$journal_sale_return_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                //echo '<pre>'.$sql."</pre><br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการขายเชื่อ --------------------------------------------


            //---------------------------- เพิ่มรายการภาษีขาย --------------------------------------------
            if((float)filter_var( $data['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) != 0.0){
                $journal_sale_return_list_debit = 0;
                $journal_sale_return_list_credit = 0;

                if((float)filter_var( $data['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) > 0){
                    $journal_sale_return_list_debit = (float)filter_var( $data['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $journal_sale_return_list_credit = 0;
                }else{
                    $journal_sale_return_list_debit = 0;
                    $journal_sale_return_list_credit = (float)filter_var( $data['credit_note_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }


                $sql = " INSERT INTO tb_journal_sale_return_list (
                    journal_sale_return_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_sale_return_list_name,
                    journal_sale_return_list_debit,
                    journal_sale_return_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_sale_return_id."',  
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '".$account_vat_sale."', 
                    '".$journal_sale_return_name."', 
                    '".$journal_sale_return_list_debit."',
                    '".$journal_sale_return_list_credit."',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";

                //echo '<pre>'.$sql."</pre><br><br>";

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            } 
            //---------------------------- สิ้นสุด เพิ่มรายการภาษีขาย --------------------------------------------

        }
    }
}
?>