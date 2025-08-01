<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Success</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #2f3e47;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .success-box {
      background-color: #ffffff;
      padding: 40px 30px;
      border-radius: 10px;
      width: 400px;
      position: relative;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }
    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 22px;
      color: #aaa;
      text-decoration: none;
    }
    .close-btn:hover {
      color: #ff0000;
    }
  </style>
</head>
<body>

  <div class="text-center success-box">
    <a href="index.php" class="close-btn">&times;</a>
    <h3 class="mb-3 text-success">Order placed successfully✅</h3>
    <p>To See Your Order</p>
    <button class="mt-3 btn btn-success" onclick="window.location.href='my-order.php'">My Order</button>
  </div>

</body>
</html>
