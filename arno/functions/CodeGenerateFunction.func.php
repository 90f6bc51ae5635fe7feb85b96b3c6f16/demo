<?PHP 
class CodeGenerate{

    public function cut2Array($str,$data = []){

        $str = str_replace("||","|",$str);
        $code = explode("|",$str);
        $arr = [];

        //print_r($code);

        for($i = 0 ; $i < count($code); $i++){
            if($code[$i] != "") {
                $word = $code[$i];
                $command = explode(":",$word);
    
                if(count($command) < 2){
                    $arr[] = array(
                        'type'=>'word',
                        'code'=>$word,
                        'value'=>$word,
                        'length'=>count($command)
                    );
                }else{

                    if(is_numeric($command[1])){
                        if($command[0] == "year"){
                            $value = strtoupper(substr($data[$command[0]],count($command[0])-1 - $command[1],$command[1]));
                        }else if ($command[0] == "month"){
                            $value = strtoupper(substr($data[$command[0]],0,2));
                        }else{
                            $value = strtoupper(substr($data[$command[0]],0,$command[1]));
                        }
                        
                    }else{
                        $value = $data[$command[0]];
                        $command[1] = -1;
                    }

                    $arr[] = array(
                        'type'=>$command[0],
                        'code'=>$word,
                        'value'=>$value,
                        'length'=>$command[1]
                    );
                }
            } 
        }

        return $arr;

    }
}



/*
require_once('../models/PurchaseRequestModel.php');

$purchase_request_model = new PurchaseRequestModel;
$code_generate = new CodeGenerate;

date_default_timezone_set('asia/bangkok');

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['customer_code'] = "A0001";
$data['employee_name'] = "thana";
$data['number'] = "0000000000";

$code = $code_generate->cut2Array("INV|year:2|month:2|number:3|-|customer_code:5|",$data);



echo "<pre>";
print_r($code);
echo "</pre>";

$last_code = "";
for($i = 0 ; $i < count($code); $i++){
    
    if($code[$i]['type'] == "number"){
        $last_code = $purchase_request_model->getPurchaseRequestLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];

        echo "<pre>";
        print_r($code[$i]['value']);
        echo "</pre>";
    }

    
}

echo "<br><br> Last Code : ".$last_code;
*/

?>
