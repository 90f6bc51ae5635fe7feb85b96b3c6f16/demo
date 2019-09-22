<?PHP 

session_start();
require_once('../models/TestCaseModel.php');



$test_case_model = new TestCaseModel;
?>
<!DOCTYPE html>
<html lang="en" style="background:#FFF;">

<head> 

<style>
table { border-collapse: collapse; }

td , th { border: 1px solid #ccc; }
</style>
</head>

<body>
    <br>
    <b>1. ใบกำกับที่ยอด Sum(invoice_customer_list_total) != invoivce_customer_total</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getInvoiceCustomerValueNotMatch();
    ?>
    <table  width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบกำกับภาษี</th>
                <th>Sum(invoice_customer_list_total)</th>
                <th>invoivce_customer_total</th>
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_customer_code']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_customer_list_total_sum'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_customer_total_price'],2); ?>
                </td>
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>2. ใบรับสินค้าที่ total + import duty + freight in != cost total</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getInvoiceSupplierTotalDutyFreigthNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบรับสินค้าที่</th>
                <th>total</th>
                <th>import duty</th>
                <th>freight in</th>
                <th>cost total</th>
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_supplier_code_gen']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_total_price'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['import_duty'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['freight_in'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_cost_total'],2); ?>
                </td>
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>3. เอกสารใบตั้งเจ้าหนี้แบบย่อที่ผลรวมไม่ตรง</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getInvoiceSupplierShortNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบรับสินค้าที่</th>
                <th>invoice_supplier_total_price</th>
                <th>invoice_supplier_short_total</th>
                <th>invoice_supplier_currency_total</th>
                <th>invoice_supplier_short_total_currency</th>
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_supplier_code_gen']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_total_price'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_short_total'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_currency_total'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_short_total_currency'],2); ?>
                </td>
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>4. เอกสารใบตั้งเจ้าหนี้แบบย่อที่ Exchange Rate ไม่ตรง</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getInvoiceSupplierExchangeRateNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบรับสินค้าที่</th>
                <th>invoice_supplier_short_list_name</th>
                <th>exchange_rate</th>
                <th>invoice_supplier_short_list_exchange_rate</th> 
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_supplier_code_gen']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_supplier_short_list_name']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['exchange_rate'],5); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_supplier_short_list_exchange_rate'],5); ?>
                </td> 
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>5. เอกสารใบวางบิลที่ยอดเงินไม่ตรงกับยอดเงินรวมในใบกำกับ</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getBillingNoteInvoiceCustomerNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบวางบิล</th>
                <th>เลขที่ใบกำกับภาษี</th>
                <th>billing_note_list_amount</th>
                <th>invoice_customer_net_price</th> 
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['billing_note_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['invoice_customer_code']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['billing_note_list_amount'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['invoice_customer_net_price'],2); ?>
                </td> 
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>6. เอกสารใบวางบิลที่ยอดเงินไม่ตรงกับยอดเงินรวมในใบลดหนี้</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getBillingNoteCreditNoteNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบวางบิล</th>
                <th>เลขที่ใบลดหนี้</th>
                <th>billing_note_list_amount</th>
                <th>credit_note_net_price</th> 
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['billing_note_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['credit_note_code']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['billing_note_list_amount'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['credit_note_net_price'],2); ?>
                </td> 
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>7. เอกสารใบวางบิลที่ยอดเงินไม่ตรงกับยอดเงินรวมในใบเพิ่มหนี้</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getBillingNoteDebitNoteNotMatch();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>เลขที่ใบวางบิล</th>
                <th>เลขที่ใบเพิมหนี้</th>
                <th>billing_note_list_amount</th>
                <th>debit_note_net_price</th> 
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['billing_note_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['debit_note_code']; ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['billing_note_list_amount'],2); ?>
                </td>
                <td align="right">
                    <?PHP echo number_format($data[$i]['debit_note_net_price'],2); ?>
                </td> 
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>8. เอกสารใบวางบิลรหัสช้ำ</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getBillingNoteCodeDouble();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>Billing note code</th>
                <th>Billing note date</th> 
                <th>Supplier</th> 
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['billing_note_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['billing_note_date']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['supplier_name_en']; ?>
                </td>
                
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>9. เอกสารเช็ครับ</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getChequeCodeDouble();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>Cheque code</th>
                <th>Cheque date</th>  
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['check_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['check_date']; ?>
                </td>  
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
    <br>
    <hr>
    <br>
    <br>
    <b>10. เอกสารเช็คจ่าย</b>
    <br>
    <br>
    <?PHP 
        $data = $test_case_model->getChequePayCodeDouble();
    ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="64px">ลำดับ</th>
                <th>Cheque pay code</th>
                <th>Cheque pay date</th>  
            </tr>
        </thead>
        <tbody>
        <?PHP 
        for($i=0; $i < count($data) ; $i++){
        ?>
            <tr>
                <td align="center">
                    <?PHP echo number_format(($i+1),0);?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['check_pay_code']; ?>
                </td>
                <td align="left">
                    <?PHP echo $data[$i]['check_pay_date']; ?>
                </td>  
            </tr>
        <?PHP 
        } 
        ?>
        </tbody>
    </table>
</body>
    
</html>
