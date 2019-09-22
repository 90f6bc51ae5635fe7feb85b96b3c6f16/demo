
<?php

require_once("BaseModel.php");
class QuotationPriceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getQuotationPriceBy(){
        $sql = " SELECT *     
        FROM tb_quotation_price 
        LEFT JOIN tb_product ON (tb_quotation_price.product_id = tb_product.product_id) 
        LEFT JOIN tb_customer ON (tb_quotation_price.customer_id = tb_customer.customer_id)  
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getQuotationPriceByID($product_id,$customer_id,$quotation_list_qty='1'){
    
        $sql = " SELECT * 
        FROM tb_quotation_price 
        WHERE product_id = '$product_id' AND customer_id = '$customer_id' AND quotation_list_qty = '$quotation_list_qty'
        ";
        // echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateQuotationPriceByID($data = []){
        $sql = " UPDATE tb_quotation_price SET     
        product_price = '".$data['product_price']."' 
        WHERE product_id = '".$data['product_id']."' AND customer_id = '".$data['customer_id']."' AND quotation_list_qty = '".$data['quotation_list_qty']."' 
         
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertQuotationPrice($data = []){
        if($data['quotation_list_qty']==''||$data['quotation_list_qty']==null){
            $data['quotation_list_qty'] = 1;
        }
        $sql = " INSERT INTO tb_quotation_price (
            product_id,
            customer_id,
            quotation_list_qty,
            product_price
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['quotation_list_qty']."', 
            '".$data['product_price']."' 
        ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteQuotationPriceByID($product_id, $customer_id){
        $sql = " DELETE FROM tb_quotation_price  WHERE product_id = '$product_id' AND customer_id = '$customer_id'  ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
