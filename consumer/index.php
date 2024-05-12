<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Godzilla</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="./index.php">Consumer</a>
        <a href="./profile.php">Profile</a>
        <div class="cart-icon" onclick="toggleCart()">🛒</div>
    </div>
    <div class="cart-popup" id="cartPopup">
        <!-- Dynamic cart items will be added here -->
        <p>Total: $<span id="totalAmount">0.00</span></p>
        <button class="btn close-btn" onclick="toggleCart()">Close</button>
        <button class="btn purchase-btn" onclick="purchase()">Purchase</button>
    </div>
    <div class="search-container">
        <input type="text" id="searchBox" placeholder="Search products..." onkeyup="searchProduct()">
    </div>
    <table>
        <tr>
            <th>Title</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Discounted Price</th>
            <th>Expiration Date</th>
            <th>Add to Cart</th>
        </tr>
        <?php
        for ($i = 0; $i < 10; $i++) {
            echo "<tr>";
            echo "<td>Title</td>";
            echo "<td>Stock</td>";
            echo "<td>Price</td>";
            echo "<td>Discounted Price</td>";
            echo "<td>Expiration Date</td>";
            echo "<td><button class='btn add-to-cart'>Add to cart</button></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div class="pagination">
        <a href="#">&laquo;</a>
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">&raquo;</a>
    </div>
    <script>
        function toggleCart() {
            var cart = document.getElementById("cartPopup");
            cart.style.display = cart.style.display === "block" ? "none" : "block";
        }
        // Implement searchProduct and addToCart functions
    </script>
</body>
</html>
