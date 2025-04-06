<?php
if (isset($_POST['product'])) {
    $products = $_POST['product'];
    echo "<h2>Selected Products:</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Product Name</th><th>Price (INR)</th></tr>";

    $total = 0;
    foreach ($products as $product_json) {
        $item = json_decode($product_json, true);
        echo "<tr>
                <td>" . htmlspecialchars($item['name']) . "</td>
                <td>" . htmlspecialchars($item['price']) . "</td>
              </tr>";
        $total += $item['price'];
    }

    echo "<tr><th>Total</th><th>INR $total</th></tr>";
    echo "</table>";
} else {
    echo "<p>No product selected.</p>";
}
?>
