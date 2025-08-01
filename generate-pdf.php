<?php
ob_clean(); // Clear any previous output
ob_start(); // Start output buffering

session_start();

// Define base URL constant
define('APPURL', 'http://localhost/dashboard/Projects/asbab_furniture');

// Include database connection
include_once('connection/conn.php');

// Load mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Instantiate mPDF
$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/tmp'
]);

// ✅ Load local CSS files (NOT URL)
$bootstrapCss = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/dashboard/Projects/asbab_furniture/css/bootstrap.min.css');
$customCss    = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/dashboard/Projects/asbab_furniture/css/custom.css');
$responsive   = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/dashboard/Projects/asbab_furniture/css/responsive.css');

// ✅ Apply CSS to PDF
$mpdf->WriteHTML($bootstrapCss, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($customCss, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($responsive, \Mpdf\HTMLParserMode::HEADER_CSS);

// ✅ Get user ID & order ID
$user_id = $_SESSION['user']['id'] ?? 0;
$order_id = $_GET['id'] ?? 0;



// ✅ Fetch order data
$select = "
SELECT 
    order_details.*,
    product.name AS product_name,
    product.price AS product_price,
    product.image AS product_image
FROM 
    order_details
JOIN 
    orders ON orders.id = order_details.order_id
JOIN 
    product ON order_details.product_id = product.id
WHERE 
    orders.user_id = $user_id AND order_details.order_id = $order_id 
ORDER BY 
    order_details.id DESC
";

$result = $conn->query($select);
$orders = $result->fetch_all(MYSQLI_ASSOC);

// ✅ Build HTML content
$html = '
<div class="container">
    <h2 class="mb-4 text-center">Product Invoice</h2>
    <table class="table table-bordered" width="100%">
        <thead class="table-light">
            <tr>
                <th style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">Product Name</th>
                <th style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">Image</th>
                <th style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">Quantity</th>
                <th style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">Price</th>
                <th style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">Total</th>
            </tr>
        </thead>
        <tbody>';

foreach ($orders as $order) {
    $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/dashboard/Projects/asbab_furniture/admin-panel/images/product/' . $order['product_image'];

    $html .= '
            <tr>
                <td  style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">' . htmlspecialchars($order['product_name']) . '</td>
                <td style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;"><img src="' . $imgPath . '" width="120" height="120"></td>
                <td style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">' . htmlspecialchars($order['qty']) . '</td>
                <td style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">$' . htmlspecialchars($order['product_price']) . '</td>
                <td style="text-align: center; display:flex; align-items: center;  padding-top:10px; padding-bottom:10px;">$' . htmlspecialchars($order['total']) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
</div>';

// ✅ Write HTML to PDF
$mpdf->WriteHTML($html);

ob_end_clean(); // Clear output buffer before sending PDF

// ✅ Output the PDF
if (isset($_GET['download'])) {
    $mpdf->Output('product-details.pdf', 'D'); // Download
} else {
    $mpdf->Output('product-details.pdf', 'I'); // Inline view
}
?>
