<?php

    require_once "../db.php";

    //reading all products
    $stmt = $db->query("select * from products") ;
    $allprd = $stmt->fetchAll() ;

    //searching products
    if ( !empty($_POST)) {
        extract($_POST);
        // Validation will be implemented

        var_dump($prd_name);
        $selected_prd = [];
        $j = 0;

        for($i = 0; $i<count($allprd); $i++) {
            $sentinel = strtolower($allprd[$i]["product_title"]);
            if(strpos($sentinel, $prd_name) !== false) {
                $selected_prd[$j] = $allprd[$i];
                $j++;
            }
        }
    }

    //pagination
    $page = $_GET["page"] ?? 1;
    $size = isset($selected_prd) ? count($selected_prd) : count($allprd);
    const PAGESIZE = 4;
    $start = ($page - 1) * PAGESIZE;
    $end = $start + PAGESIZE;
    $end = $end > $size ? $size : $end;
    $totalPage = ceil($size / PAGESIZE);


?>
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
    <form action="" method="post">
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
        <input type="text" id="searchBox" placeholder="Search products..." onkeyup="searchProduct()" name="prd_name">
    </div>
    <table>
        <tr>
            <th>Title</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Discounted Price</th>
            <th>Expiration Date</th>
            <th>Add to Cart</th>
            <!-- <th><button type="submit" class="btn" title="Add"><i>?</i></button></th> -->
        </tr>
        <?php
        $products = isset($selected_prd) ? $selected_prd : $allprd;
        for ($i = $start; $i < $end; $i++) {
            echo "<tr>
                <td>{$products[$i]["product_title"]}</td>
                <td>stock</td>
                <td>{$products[$i]["product_price"]}</td>
                <td>{$products[$i]["product_disc_price"]}</td>
                <td>{$products[$i]["product_exp_date"]}</td>
                <td>{$products[$i]["product_city"]}</td>
                <td><button class='btn add-to-cart'>Add to cart</button></td>
            </tr>";
        }
        ?>
    </table>
    <div class="pagination">
        <?php
        for($i = 1; $i<=$totalPage; $i++) {
            echo "<a href='index.php?page=$i'>$i</a>";
        }
        ?>
    </div>
    <script>
        function toggleCart() {
            var cart = document.getElementById("cartPopup");
            cart.style.display = cart.style.display === "block" ? "none" : "block";
        }
        // Implement searchProduct and addToCart functions
    </script>
    </form>
</body>
</html>
