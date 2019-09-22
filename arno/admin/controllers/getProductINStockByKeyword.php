<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductModel.php');
$keyword = $_GET['keyword'];
$stock_group_id = $_GET['stock_group_id'];

$product_model = new ProductModel;

$product = $product_model->getProductINStockBy( $stock_group_id, $keyword );

echo json_encode($product);

?>