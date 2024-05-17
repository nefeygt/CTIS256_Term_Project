<?php

    session_start();
    require_once "../db.php";

    //shopping cart initialization if its not initialized
    $_SESSION["cart"] = !isset($_SESSION["cart"]) ? [] : $_SESSION["cart"];

    $selected_prd = [];
    $allprd = [];

    //shopping cart
    if(isset($_POST["action"])) {

        $act = $_POST["action"];
        $pid = $_POST["product_id"];

        if ($act === "add_to_cart") {

            $prod = getProductById($pid);

            if($prod) {
                $_SESSION["cart"][] = $prod;
                $show_cart = true;
            } 
        }
    }

    //reading all products
    $stmt = $db->query("SELECT * from products") ;
    $allprd = $stmt->fetchAll() ;

    //searchin products
    $q = $_GET["search"] ?? '';
    if(!empty($q)) {
        $q = strtolower($q);
        foreach ($allprd as $product) {
            $temp = strtolower($product["product_title"]);
            if (strpos($temp, $q) !== false) {
                $selected_prd[] = $product;
            }
        }
    }

    $finalprd = count($selected_prd) > 0 ? $selected_prd : $allprd;

    //pagination
    $page = $_GET["page"] ?? 1;
    $size = count($finalprd);
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
    <form action="" method="get">
    <div class="navbar">
        <a href="./index.php">Consumer</a>
        <a href="./profile.php">Profile</a>
        <div class="cart-icon" onclick="toggleCart()">🛒</div>
    </div>
    <div class="cart-popup" id="cartPopup">
        <!-- Dynamic cart items will be added here -->

        <?php if(!empty($_SESSION["cart"])): ?>
            <ul>
                <?php foreach ($_SESSION["cart"] as $item): ?>
                    <li><?= $item["product_title"]?> - <?= $item["product_price"] ?></li>
                <?php endforeach; ?>
            </ul>

            <p>Total: $<span id="totalAmount">
            <?php
                $tot = 0;
                foreach ( $_SESSION["cart"] as $item) {
                    $tot += $item["product_price"];
                }
                echo $tot;
            ?>
            </span></p>
        <?php else: ?>
            <p>Your cart is EMPTY.</p>
        <?php endif; ?>

        <button class="btn close-btn" onclick="toggleCart()">Close</button>
        <button class="btn purchase-btn" onclick="purchase()">Purchase</button>
        <button class= ></button>
    </div>
    <div class="search-container">
        <input type="text" id="searchBox" placeholder="Search products..." onkeyup="searchProduct()" name="search" value="<?=$q?>">
        <button type="submit">Search</button>
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
        // $products = isset($selected_prd) ? $selected_prd : $allprd;
        for ($i = $start; $i < $end; $i++) {
            echo "<tr>
                <td>{$finalprd[$i]["product_title"]}</td>
                <td>", isInStock($finalprd[$i]["product_id"]) ? "yes stock" : "no stock" ,"</td>
                <td>{$finalprd[$i]["product_price"]}</td>
                <td>{$finalprd[$i]["product_disc_price"]}</td>
                <td>{$finalprd[$i]["product_exp_date"]}</td>
                <td>{$finalprd[$i]["product_city"]}</td>
                <td>
                    <form action = '' method = 'post'>
                        <input type='hidden' name='product_id' value='{$finalprd[$i]["product_id"]}'>
                        <input type='hidden' name='action' value='add_to_cart'>
                        <button type='submit' class='btn add-to-cart' onclick='showCart()'>Add to cart</button>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </table>
    <div class="pagination">
        <?php
        for($i = 1; $i<=$totalPage; $i++) {
            // echo "<a href='index.php?page=$i&search=", urlencode($q) ,"'>$i</a>";

            // $url = "index.php?page=$i&search=" . urlencode($q);
            // echo "<a href='$url'>$i</a>";

            echo "<a href='index.php?page=$i&search=$q'>$i</a>";
        }
        ?>
    </div>
    <?php if(count($selected_prd) !== 0): ?>
        <a href="index.php">Go back</a>
    <?php endif ?>
    <script>
        function toggleCart() {
            var cart = document.getElementById("cartPopup");
            cart.style.display = cart.style.display === "block" ? "none" : "block";
        }

        function showCart() {
            var cart = document.getElementById("cartPopup");
            cart.style.display = "block";
            cart.classList.remove("fade-out");

            setTimeout(function() {
                cart.classList.add("fade-out");
                
                setTimeout(function() {
                    cart.style.display = "none";
                    cart.classList.remove("fade-out");
                }, 5000);
            }, 10000);
        }

        // <?php if (isset($showCart) && $showCart): ?>
        //     window.onload = function() {
        //         showCart();
        //     }
        // <?php endif; ?>

        // Implement searchProduct and addToCart functions
    </script>
    </form>
</body>
</html>