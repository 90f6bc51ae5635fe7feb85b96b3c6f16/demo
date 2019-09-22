<?php

require_once("BaseModel.php");
class ProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductBy($supplier_id = '', $product_category_id = '', $product_type_id = '', $keyword  = '', $product_type_auto =''){
        
        if($supplier_id != ""){
            $supplier = "AND tb_product_supplier.supplier_id = '$supplier_id' ";
        }

        $str_type_auto = '' ;
        if($product_type_auto != ''){
            $str_type_auto = "AND product_type_auto = '$product_type_auto' ";
        }

      
       
        
        if($product_type_id != ""){
            $product_type = "AND tb_product_type.product_type_id = '$product_type_id' ";
        }



        if($product_category_id != ""){
            $product_category = "AND tb_product.product_category_id = '$product_category_id' ";
        }
        

        
        if($keyword != ""){
            $sts_keyword = " AND (product_name LIKE ('%$keyword%') OR product_code LIKE ('%$keyword%') ) ";
        }

        
        $sql = " SELECT tb_product.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_drawing, 
        product_name, 
        product_description , 
        product_type, 
        product_status ,
        product_price_1, 
        product_price_2, 
        product_price_3, 
        product_price_4, 
        product_price_5, 
        product_price_6, 
        product_price_7,
      
        product_scale_price 
        FROM tb_product 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id
        LEFT JOIN tb_product_scale ON  tb_product.product_id =  tb_product_scale.product_id 
        WHERE 1 
        $sts_keyword
        $product_type 
        $str_type_auto
        $product_category  
        GROUP BY tb_product.product_id
        ORDER BY product_code  
        "; 
 

//  echo'<pre>';
//  print_r($sql);
//  echo'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }


    function getProductLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(product_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  product_lastcode 
        FROM tb_product
        WHERE product_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['product_lastcode'];
        }

    }


    function getProductOtherBy( $keyword  = ''){
        
        $sql = " SELECT tb_product.product_id, CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description , product_type, product_status ,
        product_price_1, product_price_2, product_price_3, product_price_4, product_price_5, product_price_6, product_price_7 
        FROM tb_product 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
        WHERE stock_event = '0' 
        AND (product_name LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') )
        GROUP BY tb_product.product_id
        ORDER BY product_code  
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
    

    function getProductINStockBy($stock_group_id = '', $keyword  = ''){
        
        if($stock_group_id != ""){
            $str_stock_group = "AND tb_stock_report.stock_group_id = '$stock_group_id' ";
        } 
        

        
        if($keyword != ""){
            $sts_keyword = " AND (product_name LIKE ('%$keyword%') OR product_code LIKE ('%$keyword%') ) ";
        }

        
        $sql = " SELECT * 
        FROM tb_stock_report 
        LEFT JOIN tb_product ON tb_stock_report.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
        WHERE 1 
        $str_stock_group
        $sts_keyword 
        GROUP BY tb_product.product_id
        ORDER BY product_code  
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


    function getProduct(){
        
        
        $sql = " SELECT product_id, CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description   
        FROM tb_product 
        ORDER BY product_name  
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
    function getProductByProductCode($product_code =''){
        
        
        $sql = " SELECT * 
        FROM tb_product 
        WHERE product_code LIKE ('$product_code')
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

    function getProductByID($id){
        $sql = " SELECT * 
        FROM tb_product  
        WHERE product_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getProductByIDMobile($id){
        $sql = " SELECT * 
        FROM tb_product   
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE product_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getProductViewByID($id){
        $sql = " SELECT * 
        FROM tb_product 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id
        
        WHERE tb_product.product_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getProductByCode($product_code){
        $sql = "SELECT * 
        FROM tb_product 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id 
        WHERE product_code = '$product_code' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getProductScaleByID($product_ID,$product_scale_qty){
        $sql = "SELECT *
        FROM tb_product_scale 
        WHERE product_id = '$product_ID' AND product_scale_qty <= '$product_scale_qty' 
        ORDER BY product_scale_qty DESC 
        LIMIT 1
        ";
        // return $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getProductScaleByProductID($product_ID=''){
        $sql = "SELECT *
        FROM tb_product_scale 
        WHERE product_id = '$product_ID' 
        ORDER BY product_scale_qty  
        ";
        $data =[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getProductCostByCode($stock_group_id, $product_code, $stock_date){

        $sql ="SELECT `table_name`,`stock_group_id`  
            FROM tb_stock_group 
            WHERE tb_stock_group.stock_group_id = '$stock_group_id' ";

        $stock = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close(); 
        } 

        $sql = "SELECT * 
        FROM  tb_product 
        LEFT JOIN ".$stock['table_name']." ON tb_product.product_id = ".$stock['table_name'].".product_id  
        WHERE product_code = '$product_code' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$stock_date','%d-%m-%Y %H:%i:%s') 
        ORDER BY stock_id desc LIMIT 0,1 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        } else {
            return [];
        }

    }

    function getProductDataByID($product_id,$stock_group_id){
        $sql = "SELECT table_name FROM tb_stock_group WHERE tb_stock_group.stock_group_id = '$stock_group_id'";
        $table_name ="";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $table_name = $row['table_name'];
            }
            $result->close();
        }

        $sql_in = "SELECT SUM(qty) 
        FROM ".$table_name."  
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'in' ";

        $sql_out = "SELECT SUM(qty) 
        FROM ".$table_name." 
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'out' ";

        $sql = " SELECT product_name, product_buyprice as product_price, (IFNULL(($sql_in),0) - IFNULL(($sql_out),0)) as product_qty  
        FROM tb_product as tb 
        LEFT JOIN tb_product_supplier ON tb.product_id = tb_product_supplier.product_id 
        WHERE tb.product_id = '$product_id' 
        AND product_supplier_status = 'Active' 
        ";

        //echo $sql;
        

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getProductDataByCode($product_code,$stock_group_id,$qty){
        $sql = "SELECT table_name FROM tb_stock_group WHERE tb_stock_group.stock_group_id = '$stock_group_id'";
        $table_name ="";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $table_name = $row['table_name'];
            }
            $result->close();
        }

        $sql_in = "SELECT SUM(qty) 
        FROM ".$table_name."  
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'in' ";

        $sql_out = "SELECT SUM(qty) 
        FROM ".$table_name." 
        WHERE ".$table_name.".product_id = tb.product_id 
        AND stock_type = 'out' ";

        $sql = " SELECT product_code, product_name, product_buyprice as product_price, $qty as product_qty ,product_scale_price 
        FROM tb_product as tb 
        LEFT JOIN tb_product_supplier ON tb.product_id = tb_product_supplier.product_id 
       
        WHERE tb.product_code = '".static::$db->real_escape_string($product_code)."' 
        AND product_supplier_status = 'Active' 
        ";

        //echo $sql;
        

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }




    
    

    function updateProductByID($id,$data = []){
        $sql = " UPDATE tb_product SET  
        product_code_first = '".static::$db->real_escape_string($data['product_code_first'])."', 
        product_code = '".static::$db->real_escape_string($data['product_code'])."', 
        product_name = '".static::$db->real_escape_string($data['product_name'])."', 
        product_group = '".static::$db->real_escape_string($data['product_group'])."', 
        product_barcode = '".static::$db->real_escape_string($data['product_barcode'])."', 
        product_description = '".static::$db->real_escape_string($data['product_description'])."', 
        product_type = '".static::$db->real_escape_string($data['product_type'])."', 
        product_unit = '".static::$db->real_escape_string($data['product_unit'])."', 
        product_drawing = '".static::$db->real_escape_string($data['product_drawing'])."', 
        product_logo = '".static::$db->real_escape_string($data['product_logo'])."',
        product_status = '".static::$db->real_escape_string($data['product_status'])."', 
        product_category_id = '".static::$db->real_escape_string($data['product_category_id'])."', 
        buy_account_id = '".static::$db->real_escape_string($data['buy_account_id'])."', 
        sale_account_id = '".static::$db->real_escape_string($data['sale_account_id'])."'  
        WHERE product_id = '".static::$db->real_escape_string($id)."' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateProductPriceByID($id,$data = []){
        $sql = " UPDATE tb_product SET 
        product_price_1 = '".static::$db->real_escape_string($data['product_price_1'])."', 
        product_price_2 = '".static::$db->real_escape_string($data['product_price_2'])."', 
        product_price_3 = '".static::$db->real_escape_string($data['product_price_3'])."', 
        product_price_4 = '".static::$db->real_escape_string($data['product_price_4'])."', 
        product_price_5 = '".static::$db->real_escape_string($data['product_price_5'])."', 
        product_price_6 = '".static::$db->real_escape_string($data['product_price_6'])."', 
        product_price_7 = '".static::$db->real_escape_string($data['product_price_7'])."'   
        WHERE product_id = '".static::$db->real_escape_string($id)."' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertProduct($data = []){
        $sql = " INSERT INTO tb_product ( 
            product_code_first, 
            product_code,
            product_name,
            product_group,
            product_barcode,
            product_description,
            product_type,
            product_unit,
            product_drawing,
            product_logo,
            product_status, 
            product_category_id,
            buy_account_id,
            sale_account_id
        ) VALUES ( 
            '".static::$db->real_escape_string($data['product_code_first'])."', 
            '".static::$db->real_escape_string($data['product_code'])."', 
            '".static::$db->real_escape_string($data['product_name'])."', 
            '".static::$db->real_escape_string($data['product_group'])."', 
            '".static::$db->real_escape_string($data['product_barcode'])."', 
            '".static::$db->real_escape_string($data['product_description'])."', 
            '".static::$db->real_escape_string($data['product_type'])."', 
            '".static::$db->real_escape_string($data['product_unit'])."', 
            '".static::$db->real_escape_string($data['product_drawing'])."', 
            '".static::$db->real_escape_string($data['product_logo'])."', 
            '".static::$db->real_escape_string($data['product_status'])."', 
            '".static::$db->real_escape_string($data['product_category_id'])."', 
            '".static::$db->real_escape_string($data['buy_account_id'])."', 
            '".static::$db->real_escape_string($data['sale_account_id'])."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }




    function deleteProductByID($id){
        $sql = " DELETE FROM tb_product WHERE product_id = '".static::$db->real_escape_string($id)."' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>