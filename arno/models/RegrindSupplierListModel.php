<?php

require_once("BaseModel.php");
class RegrindSupplierListModel extends BaseModel
{

    function __construct()
    {
        if (!static::$db) {
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRegrindSupplierListBy($regrind_supplier_id)
    {
        $sql = " SELECT tb_regrind_supplier_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        regrind_supplier_list_id, 
        regrind_supplier_list_qty,
        regrind_supplier_list_remark 
        FROM tb_regrind_supplier_list LEFT JOIN tb_product ON tb_regrind_supplier_list.product_id = tb_product.product_id 
        WHERE regrind_supplier_id = '$regrind_supplier_id' 
        ORDER BY regrind_supplier_list_id 
        ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getRegrindSupplierListByMobile($supplier_id = "", $user_id = "", $product_id = [])
    {
        $str_product_id = "'0'";
        $str_user = "";
        if ($user_id != "") {
            $str_user = " AND employee_id = '$user_id' ";
        }
        if ($supplier_id != "") {
            $str_supplier = " AND supplier_id = '$supplier_id'   ";
        }
        if (is_array($product_id) && count($product_id) > 0) {

            $str_product_id = "";
            for ($i = 0; $i < count($product_id); $i++) {
                $str_product_id .= " '" . $product_id[$i] . "' ";
                if ($i + 1 < count($product_id)) {
                    $str_product_id .= ",";
                }
            }
        } else if ($product_id != '') {
            $str_product_id = "" . $product_id . "";
        } else {
            $str_product_id = "'0'";
        }
        $sql = "SELECT 
        '1_Send' as 'regrind_type',
        tb_regrind_supplier_list.product_id, 
        tb_regrind_supplier.regrind_supplier_id as 'id',
        CONCAT(product_code_first,product_code) as product_code, 
        regrind_supplier_code as code,
        product_name,   
        regrind_supplier_list_id, 
        regrind_supplier_list_qty as qty,
        '' as scrap_qty,
        regrind_supplier_list_remark ,
        regrind_supplier_date as 'date' ,
        'Send' as 'complete'
        FROM tb_regrind_supplier_list 
        LEFT JOIN tb_product ON tb_regrind_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
        WHERE tb_regrind_supplier_list.product_id IN ($str_product_id)
        $str_user
        $str_supplier
        ORDER BY STR_TO_DATE(`date`,'%d-%m-%Y %H:%i:%s') DESC , code DESC ,regrind_supplier_list_id DESC
        ";

        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function insertRegrindSupplierList($data = [])
    {
        $sql = " INSERT INTO tb_regrind_supplier_list (
            regrind_supplier_id,
            product_id,
            regrind_supplier_list_qty,
            regrind_supplier_list_remark,
            addby,
            adddate 
        ) VALUES (
            '" . $data['regrind_supplier_id'] . "', 
            '" . $data['product_id'] . "', 
            '" . $data['regrind_supplier_list_qty'] . "', 
            '" . $data['regrind_supplier_list_remark'] . "',
            '" . $data['addby'] . "', 
            NOW() 
        ); 
        ";


        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        } else {
            return 0;
        }
    }

    function updateRegrindSupplierListById($data, $id)
    {

        $sql = " UPDATE tb_regrind_supplier_list 
            SET product_id = '" . $data['product_id'] . "', 
            regrind_supplier_list_qty = '" . $data['regrind_supplier_list_qty'] . "',
            regrind_supplier_list_remark = '" . $data['regrind_supplier_list_remark'] . "' 
            WHERE regrind_supplier_list_id = '$id'
        ";


        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }
    function updateRegrindSupplierListCompleteById($data, $id)
    {

        $sql = " UPDATE tb_regrind_supplier_list 
            SET  
            regrind_supplier_list_complete = '" . $data['regrind_supplier_list_complete'] . "' ,
            updateby = '" . $data['updateby'] . "' ,
            lastupdate = NOW() 
            WHERE regrind_supplier_list_id = '$id'
        ";
        // echo $sql;

        if (mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            return true;
        } else {
            return false;
        }
    }
    function deleteRegrindSupplierListByID($id)
    {
        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_list_id = '$id' ";
        mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function deleteRegrindSupplierListByRegrindSupplierID($id)
    {
        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' ";
        mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function deleteRegrindSupplierListByRegrindSupplierIDNotIN($id, $data)
    {
        $str = '';
        if (is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i] != "") {
                    $str .= $data[$i];
                    if ($i + 1 < count($data)) {
                        $str .= ',';
                    }
                }
            }
        } else if ($data != '') {
            $str = $data;
        } else {
            $str = '0';
        }

        if ($str == '') {
            $str = '0';
        }

        $sql = "DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' AND regrind_supplier_list_id NOT IN ($str) ";
        mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT);
    }

    function getRegrindSupplierListBySupplierAndProductID($supplier_id = '', $product_id = [])
    {
        if (is_array($product_id) && count($product_id) > 0) {

            $str_product_id = "";
            for ($i = 0; $i < count($product_id); $i++) {
                $str_product_id .= " '" . $product_id[$i] . "' ";
                if ($i + 1 < count($product_id)) {
                    $str_product_id .= ",";
                }
            }
        } else if ($product_id != '') {
            $str_product_id = "" . $product_id . "";
        } else {
            $str_product_id = "'0'";
        }
        $sql = "SELECT * 
            FROM tb_regrind_supplier_list 
            LEFT JOIN tb_regrind_supplier on tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
            WHERE tb_regrind_supplier_list.product_id in ($str_product_id) 
            AND tb_regrind_supplier.supplier_id = '$supplier_id'
            ORDER BY tb_regrind_supplier_list.regrind_supplier_list_id
        ";
        if ($result = mysqli_query(static::$db, $sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
}
