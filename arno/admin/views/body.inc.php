<?php
 

    if(!isset($_GET['app'])){
        require_once("modules/my_customer/views/index.inc.php");
    }else if($_GET['app'] == "notification"){
        require_once("modules/notification/views/index.inc.php");
    }else if($_GET['app'] == "user_profile"){
        require_once("modules/user_profile/views/index.inc.php");
    }
    
    /* ----------------------------------- ระบบพื้นฐาน --------------------------------- */
    
    else if($_GET['app'] == "setting"){
        require_once("modules/setting/views/index.inc.php");
    }else if($_GET['app'] == "employee"){
        require_once("modules/user/views/index.inc.php");
    }else if($_GET['app'] == "employee_position"){
        require_once("modules/user_position/views/index.inc.php");
    }else if($_GET['app'] == "employee_license"){
        require_once("modules/user_license/views/index.inc.php");
    }
    

    else if($_GET['app'] == "product"){
        require_once("modules/product/views/index.inc.php");
    }else if($_GET['app'] == "product_category"){
        require_once("modules/product_category/views/index.inc.php");
    }else if($_GET['app'] == "product_type"){
        require_once("modules/product_type/views/index.inc.php");
    }else if($_GET['app'] == "product_group"){
        require_once("modules/product_group/views/index.inc.php");
    }else if($_GET['app'] == "product_unit"){
        require_once("modules/product_unit/views/index.inc.php");
    }
    
      
    else if($_GET['app'] == "customer"){
        require_once("modules/customer/views/index.inc.php");
    }else if($_GET['app'] == "customer_end_users"){
        require_once("modules/customer_end_user/views/index.inc.php");
    }else if($_GET['app'] == "customer_account"){
        require_once("modules/customer_account/views/index.inc.php");
    }else if($_GET['app'] == "customer_contact"){
        require_once("modules/customer_contact/views/index.inc.php");
    }else if($_GET['app'] == "customer_logistic"){
        require_once("modules/customer_logistic/views/index.inc.php");
    }else if($_GET['app'] == "customer_holiday"){
        require_once("modules/customer_holiday/views/index.inc.php");
    }
    
    
    else if($_GET['app'] == "supplier"){
        require_once("modules/supplier/views/index.inc.php");
    }else if($_GET['app'] == "supplier_account"){
        require_once("modules/supplier_account/views/index.inc.php");
    }else if($_GET['app'] == "supplier_contact"){
        require_once("modules/supplier_contact/views/index.inc.php");
    }else if($_GET['app'] == "supplier_logistic"){
        require_once("modules/supplier_logistic/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบพื้นฐาน// ---------------------------------------------- */
    
    

    /* ----------------------------------- ระบบการอนุมัติ ---------------------------------------------- */
    else if($_GET['app'] == "approve_setting"){
        require_once("modules/approve_setting/views/index.inc.php");
    }
    else if($_GET['app'] == "quotation_supplier"){
        require_once("modules/quotation_supplier/views/index.inc.php");
    }
    else if($_GET['app'] == "approve_purchase_request"){
        require_once("modules/approve_purchase_request/views/index.inc.php");
    }
    else if($_GET['app'] == "approve_purchase_order"){
        require_once("modules/approve_purchase_order/views/index.inc.php");
    }
    else if($_GET['app'] == "approve_request_test"){
        require_once("modules/approve_request_test/views/index.inc.php");
    }
    else if($_GET['app'] == "approve_customer"){
        require_once("modules/approve_customer/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบการอนุมัติ// ---------------------------------------------- */




    /* ----------------------------------- ระบบพนักงานขาย ---------------------------------------------- */
    else if($_GET['app'] == "sale_benefit_setting"){
        require_once("modules/sale_benefit_setting/views/index.inc.php");
    }
    else if($_GET['app'] == "sale_employee"){
        require_once("modules/sale_employee/views/index.inc.php");
    }
    else if($_GET['app'] == "my_customer"){
        require_once("modules/my_customer/views/index.inc.php");
    }
    else if($_GET['app'] == "my_customer_end_users"){
        require_once("modules/my_customer_end_user/views/index.inc.php");
    }
    else if($_GET['app'] == "price_list"){
        require_once("modules/price_list/views/index.inc.php");
    }
    else if($_GET['app'] == "product_special"){
        require_once("modules/product_special/views/index.inc.php");
    }
    /* ----------------------------------- //ระบบพนักงานขาย// ---------------------------------------------- */



    /* ----------------------------------- ระบบสั่งสินค้าทดลอง ---------------------------------------------- */
    
    else if($_GET['app'] == "request_test"){
        require_once("modules/request_test/views/index.inc.php");
    }else if($_GET['app'] == "request_standard"){
        require_once("modules/request_standard/views/index.inc.php");
    }else if($_GET['app'] == "request_special"){
        require_once("modules/request_special/views/index.inc.php");
    }else if($_GET['app'] == "request_regrind"){
        require_once("modules/request_regrind/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบสั่งสินค้าทดลอง// ---------------------------------------------- */
   



    /* ----------------------------------- ระบบจัดซื้อ ---------------------------------------------- */

    else if($_GET['app'] == "regrind_supplier"){
        require_once("modules/regrind_supplier/views/index.inc.php");
    }else if($_GET['app'] == "regrind_supplier_receive"){
        require_once("modules/regrind_supplier_receive/views/index.inc.php");
    }else if($_GET['app'] == "regrind_list"){
        require_once("modules/regrind_list/views/index.inc.php");
    }else if ($_GET['app'] == "delivery_note_supplier_send"){
        require_once("modules/delivery_note_supplier_send/views/index.inc.php");
    }else if($_GET['app'] == "delivery_note_customer"){
        require_once("modules/delivery_note_customer/views/index.inc.php");
    }else if($_GET['app'] == "delivery_note_supplier"){
        require_once("modules/delivery_note_supplier/views/index.inc.php");
    }else if($_GET['app'] == "delivery_note_customer_receive"){
        require_once("modules/delivery_note_customer_receive/views/index.inc.php");
    }else if($_GET['app'] == "purchase_request"){
        require_once("modules/purchase_request/views/index.inc.php");
    }else if($_GET['app'] == "customer_purchase_order"){
        require_once("modules/customer_purchase_order/views/index.inc.php");
    }else if($_GET['app'] == "purchase_order"){
        require_once("modules/purchase_order/views/index.inc.php");
        
    }else if($_GET['app'] == "invoice_supplier_tmp"){
        require_once("modules/invoice_supplier_tmp/views/index.inc.php");

    }else if($_GET['app'] == "invoice_supplier"){
        require_once("modules/invoice_supplier/views/index.inc.php");
    }else if($_GET['app'] == "exchange_rate_baht"){
        require_once("modules/exchange_rate_baht/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบจัดซื้อ// ---------------------------------------------- */
    
    
    
    
    
    /* ----------------------------------- ระบบขายสินค้า ---------------------------------------------- */

    else if($_GET['app'] == "quotation"){
        require_once("modules/quotation/views/index.inc.php");
    }else if($_GET['app'] == "invoice_customer"){
        require_once("modules/invoice_customer/views/index.inc.php");
    }
    
    /* ----------------------------------- //ระบบขายสินค้า// ---------------------------------------------- */
   

    /* ----------------------------------- ระบบบัญชี ---------------------------------------------- */
    else if($_GET['app'] == "asset"){
        require_once("modules/asset/views/index.inc.php");
    }else if($_GET['app'] == "asset_category"){
        require_once("modules/asset_category/views/index.inc.php");
    }else if($_GET['app'] == "asset_account_group"){
        require_once("modules/asset_account_group/views/index.inc.php");
    }else if($_GET['app'] == "asset_department"){
        require_once("modules/asset_department/views/index.inc.php");
    }else if($_GET['app'] == "credit_note"){
        require_once("modules/credit_note/views/index.inc.php");
    }else if($_GET['app'] == "credit_note_supplier"){
        require_once("modules/credit_note_supplier/views/index.inc.php");
    }else if($_GET['app'] == "debit_note"){
        require_once("modules/debit_note/views/index.inc.php");
    }else if($_GET['app'] == "billing_note"){
        require_once("modules/billing_note/views/index.inc.php");
    }else if($_GET['app'] == "official_receipt"){
        require_once("modules/official_receipt/views/index.inc.php");
    }else if($_GET['app'] == "account"){
        require_once("modules/account/views/index.inc.php");
    }else if($_GET['app'] == "account_setting"){
        require_once("modules/account_setting/views/index.inc.php");
    }else if($_GET['app'] == "finance_debit_account"){
        require_once("modules/finance_debit_account/views/index.inc.php");
    }else if($_GET['app'] == "finance_credit_account"){
        require_once("modules/finance_credit_account/views/index.inc.php");
    }else if($_GET['app'] == "paper"){
        require_once("modules/paper/views/index.inc.php");
    }else if($_GET['app'] == "finance_credit"){
        require_once("modules/finance_credit/views/index.inc.php");
    }else if($_GET['app'] == "finance_debit"){
        require_once("modules/finance_debit/views/index.inc.php");
    }else if($_GET['app'] == "summit_dedit"){
        require_once("modules/invoice_customer_begin/views/index.inc.php");
    }else if($_GET['app'] == "summit_credit"){
        require_once("modules/invoice_supplier_begin/views/index.inc.php");
    }else if($_GET['app'] == "summit_product"){
        require_once("modules/summit_product/views/index.inc.php");
    }else if($_GET['app'] == "summit_check_pre_receipt"){
        require_once("modules/summit_check_pre_receipt/views/index.inc.php");
    }else if($_GET['app'] == "summit_check_pre_pay"){
        require_once("modules/summit_check_pre_pay/views/index.inc.php");
    }else if($_GET['app'] == "summit_account"){
        require_once("modules/account_begin/views/index.inc.php");
    }else if($_GET['app'] == "bank"){
        require_once("modules/bank/views/index.inc.php");
    }else if($_GET['app'] == "bank_account"){
        require_once("modules/bank_account/views/index.inc.php");
    }else if($_GET['app'] == "bank_check_in"){
        require_once("modules/bank_check_in/views/index.inc.php");
    }else if($_GET['app'] == "bank_check_in_deposit"){
        require_once("modules/bank_check_in_deposit/views/index.inc.php");
    }else if($_GET['app'] == "bank_check_in_pass"){
        require_once("modules/bank_check_in_pass/views/index.inc.php");
    }else if($_GET['app'] == "bank_check_pay"){
        require_once("modules/bank_check_pay/views/index.inc.php");
    }else if($_GET['app'] == "bank_check_pay_pass"){
        require_once("modules/bank_check_pay_pass/views/index.inc.php");
    }else if($_GET['app'] == "other_expense"){
        require_once("modules/other_expense/views/index.inc.php");
    }else if($_GET['app'] == "credit_purchasing"){
        require_once("modules/credit_purchasing/views/index.inc.php");
    }else if($_GET['app'] == "journal_general"){
        require_once("modules/journal_general/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_01"){
        require_once("modules/journal_purchase/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_02"){
        require_once("modules/journal_sale/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_03"){
        require_once("modules/journal_cash_receipt/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_04"){
        require_once("modules/journal_cash_payment/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_05"){
        require_once("modules/journal_purchase_return/views/index.inc.php");
    }else if($_GET['app'] == "journal_special_06"){
        require_once("modules/journal_sale_return/views/index.inc.php");
    }else if($_GET['app'] == "maintenance"){
        require_once("modules/maintenance/views/index.inc.php");
    }else if($_GET['app'] == "paper_lock"){
        require_once("modules/paper_lock/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบบัญชี// ---------------------------------------------- */


    /* ----------------------------------- ระบบรายงาน ---------------------------------------------- */

    else if($_GET['app'] == "report_creditor_01"){
        require_once("modules/report_creditor_01/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_02"){
        require_once("modules/report_creditor_02/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_03"){
        require_once("modules/report_creditor_03/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_04"){
        require_once("modules/report_creditor_04/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_05"){
        require_once("modules/report_creditor_05/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_06"){
        require_once("modules/report_creditor_06/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_07"){
        require_once("modules/report_creditor_07/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_08"){
        require_once("modules/report_creditor_08/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_09"){
        require_once("modules/report_creditor_09/views/index.inc.php");
    }else if($_GET['app'] == "report_creditor_10"){
        require_once("modules/report_creditor_10/views/index.inc.php");
    }

    
    else if($_GET['app'] == "report_debtor_01"){
        require_once("modules/report_debtor_01/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_02"){
        require_once("modules/report_debtor_02/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_03"){
        require_once("modules/report_debtor_03/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_04"){
        require_once("modules/report_debtor_04/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_05"){
        require_once("modules/report_debtor_05/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_06"){
        require_once("modules/report_debtor_06/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_07"){
        require_once("modules/report_debtor_07/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_08"){
        require_once("modules/report_debtor_08/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_09"){
        require_once("modules/report_debtor_09/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_10"){
        require_once("modules/report_debtor_10/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_11"){
        require_once("modules/report_debtor_11/views/index.inc.php");
    }else if($_GET['app'] == "report_debtor_12"){
        require_once("modules/report_debtor_12/views/index.inc.php");
    }


    else if($_GET['app'] == "report_tax_01"){
        require_once("modules/report_tax_01/views/index.inc.php");
    }else if($_GET['app'] == "report_tax_02"){
        require_once("modules/report_tax_02/views/index.inc.php");
    }else if($_GET['app'] == "report_tax_03"){
        require_once("modules/report_tax_03/views/index.inc.php");
    }


    
    else if($_GET['app'] == "report_account_01"){
        require_once("modules/report_account_01/views/index.inc.php");
    }else if($_GET['app'] == "report_account_03"){
        require_once("modules/report_account_03/views/index.inc.php");
    }else if($_GET['app'] == "report_account_04"){
        require_once("modules/report_account_04/views/index.inc.php");
    }else if($_GET['app'] == "report_account_05"){
        require_once("modules/report_account_05/views/index.inc.php");
    }else if($_GET['app'] == "report_account_06"){
        require_once("modules/report_account_06/views/index.inc.php");
    }else if($_GET['app'] == "report_account_07"){
        require_once("modules/report_account_07/views/index.inc.php");
    }else if($_GET['app'] == "report_account_08"){
        require_once("modules/report_account_08/views/index.inc.php");
    }else if($_GET['app'] == "report_account_09"){
        require_once("modules/report_account_09/views/index.inc.php");
    }else if($_GET['app'] == "report_account_10"){
        require_once("modules/report_account_10/views/index.inc.php");
    }else if($_GET['app'] == "report_account_11"){
        require_once("modules/report_account_11/views/index.inc.php");
    }

    else if($_GET['app'] == "report_stock_01"){
        require_once("modules/report_stock_01/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_02"){
        require_once("modules/report_stock_02/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_03"){
        require_once("modules/report_stock_03/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_04"){
        require_once("modules/report_stock_04/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_05"){
        require_once("modules/report_stock_05/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_06"){
        require_once("modules/report_stock_06/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_07"){
        require_once("modules/report_stock_07/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_08"){
        require_once("modules/report_stock_08/views/index.inc.php");
    }  
    else if($_GET['app'] == "report_stock_10"){
        require_once("modules/report_stock_10/views/index.inc.php");
    }
    else if($_GET['app'] == "report_stock_11"){
        require_once("modules/report_stock_11/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบรายงาน// ---------------------------------------------- */
   



    /* ----------------------------------- ระบบคลังสินค้า ---------------------------------------------- */
    
    else if($_GET['app'] == "search_product"){
        require_once("modules/search_product/views/index.inc.php");
    }
    else if($_GET['app'] == "product_detail"){
        require_once("modules/product_detail/views/index.inc.php");
    }
    
    else if($_GET['app'] == "stock_move"){
        require_once("modules/stock_move/views/index.inc.php");
    }else if($_GET['app'] == "stock_change_product"){
        require_once("modules/stock_change_product/views/index.inc.php");
    }else if($_GET['app'] == "stock_product_set"){
        require_once("modules/stock_product_set/views/index.inc.php");
    }else if($_GET['app'] == "stock_type"){
        require_once("modules/stock_type/views/index.inc.php");
    }else if($_GET['app'] == "stock_group"){
        require_once("modules/stock_group/views/index.inc.php");
    }else if($_GET['app'] == "stock"){
        require_once("modules/stock/views/index.inc.php");
    }else if($_GET['app'] == "stock_list"){
        require_once("modules/stock_list/views/index.inc.php");
    }else if($_GET['app'] == "stock_in"){
        require_once("modules/stock_in/views/index.inc.php");
    }else if($_GET['app'] == "stock_out"){
        require_once("modules/stock_out/views/index.inc.php");
    }else if($_GET['app'] == "stock_issue"){
        require_once("modules/stock_issue/views/index.inc.php");
    }else if($_GET['app'] == "maintenance_stock"){
        require_once("modules/maintenance_stock/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบคลังสินค้า// ---------------------------------------------- */
   
    


    else if($_GET['app'] == "job"){
        require_once("modules/job/views/index.inc.php");
    }

    /* ----------------------------------- //ระบบ Customer Service Report// ---------------------------------------------- */
    else if($_GET['app'] == "customer_service_report"){
        require_once("modules/customer_service_report/views/index.inc.php");
    }

?>