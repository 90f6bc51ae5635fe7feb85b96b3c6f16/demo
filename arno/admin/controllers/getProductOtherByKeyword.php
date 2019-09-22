<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductModel.php');
$keyword = $_GET['keyword'];

$product_model = new ProductModel;

$product = $product_model->getProductOtherBy($keyword );

echo json_encode($product);

?>