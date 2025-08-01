<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';
require_once 'connection/conn.php';

function send_mail(string $email, int $order_id): void {
    global $conn;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email address.";
        return;
    }

    // 🟡 Fetch order main info: total & discount
    $order_stmt = $conn->prepare("
        SELECT total_price, coupon_value 
        FROM orders 
        WHERE id = ? AND user_id = ?
        LIMIT 1
    ");
    $order_stmt->bind_param("ii", $order_id, $_SESSION['user']['id']);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();

    if (!$order_result || $order_result->num_rows === 0) {
        echo "❌ Order not found.";
        return;
    }

    $order_data = $order_result->fetch_assoc();
    $total_price = $order_data['total_price'];
    $discount = $order_data['coupon_value'] ?? 0;

    // 🟡 Fetch product-wise summary
    $stmt = $conn->prepare("
        SELECT p.name, od.qty, od.total 
        FROM order_details od 
        JOIN product p ON od.product_id = p.id 
        WHERE od.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        echo "❌ No items found for this order.";
        return;
    }

    $order_items_html = '';
    while ($item = $result->fetch_assoc()) {
        $order_items_html .= "
            <tr>
                <td style='padding: 10px; border: 1px solid #ccc;'>{$item['name']}</td>
                <td style='padding: 10px; border: 1px solid #ccc;'>{$item['qty']}</td>
                <td style='padding: 10px; border: 1px solid #ccc;'>$" . number_format($item['total'], 2) . "</td>
            </tr>";
    }

    // 🟢 Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mdrasel551219@gmail.com';
        $mail->Password   = 'wcpu pwdn qbat duwh'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mdrasel551219@gmail.com', 'Asbab Furniture');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "✅ Your Order #$order_id Confirmation";

        // 🟣 Discount line (conditionally show)
        $discount_html = '';
        if ($discount > 0) {
            $discount_html = "<p><strong>Discount Applied:</strong> -$" . number_format($discount, 2) . "</p>";
        }

        // Email Body
        $mail->Body = "
        <html>
          <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
            <div style='max-width: 700px; background: #fff; padding: 30px; border-radius: 8px;'>
              <h2 style='color: green;'>✅ Thank You for Your Order!</h2>
              <p><strong>Order ID:</strong> #$order_id</p>
              <p><strong>Total Amount:</strong> $" . number_format($total_price, 2) . "</p>
              $discount_html

              <h4 style='margin-top: 20px;'>Order Summary:</h4>
              <table style='border-collapse: collapse; width: 100%; margin-top: 10px;'>
                <thead>
                  <tr>
                    <th style='padding: 10px; border: 1px solid #ccc;'>Product</th>
                    <th style='padding: 10px; border: 1px solid #ccc;'>Quantity</th>
                    <th style='padding: 10px; border: 1px solid #ccc;'>Total</th>
                  </tr>
                </thead>
                <tbody>
                  $order_items_html
                </tbody>
              </table>

              <p style='margin-top: 25px; font-size: 13px; color: #888;'>If you have any questions, reply to this email or contact our support.</p>
            </div>
          </body>
        </html>";

        $mail->send();
        echo "✅ Order confirmation email sent to <strong>$email</strong>.";
    } catch (Exception $e) {
        echo "❌ Email could not be sent. Mailer Error: <strong>{$mail->ErrorInfo}</strong>";
    }
}


