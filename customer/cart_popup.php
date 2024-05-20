<?php
// Initialize the cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
echo "<div class='max-w-lg mx-auto bg-white p-4 shadow rounded' style='width: 350px;' >";

// Display the cart items
if (!empty($_SESSION['cart'])) {
    // var_dump($_SESSION["cart"]);
    echo "<h1 style='background-color:powderblue; width: max-content; padding:10px; border-radius:25px; margin-bottom:10px;'>Shopping Cart</h1>";
    echo "<hr>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) {
            $maxQuantity = max($product['stock'], $quantity); // limit max quantity to the stock available

            echo "<li class='flex justify-between items-center p-2 border-b border-gray-200'>";
            
            echo "<div class='cart' style='width: 4000px; margin-right:15px; '>";
            echo htmlspecialchars($product['product_title']);
            echo "</div>";

            echo "<div class='cart' style='width: 4000px; display:flex; '>";
            echo "<form action='change_amount.php' method='post' class='quantity-form'>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($productId) . "'>";
            echo "<input type='hidden' name='action' value='decrease'>";
            echo "<button type='submit'>-</button>";
            echo "</form>";

            echo "<span class='mx-2'>" . htmlspecialchars($quantity) . "</span>";

            echo "<form action='change_amount.php' method='post' class='quantity-form'>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($productId) . "'>";
            echo "<input type='hidden' name='action' value='increase'>";
            echo "<button type='submit'>+</button>";
            echo "</form>";
            echo "</div>";

            echo "<div class='cart' style='width: 4000px; '>";
            echo " : " . ($product['product_disc_price'] * $quantity) . " tl"; 
            echo "</div>";

            echo "<div class='cart' style='width: 4000px; '>";
            echo "<a href='remove_from_cart.php?product_id=" . urlencode($productId) . "' class='ml-4 text-red-500 hover:text-red-700'>Remove</a>";
            echo "</div>";

            echo "</li>";
        }
    }
    echo "</ul>";

    $tot = 0;
    $dtot = 0;
    foreach($_SESSION['cart'] as $productId => $quantity) {
        $product = getProductById($productId);
        $dtot += ($product['product_disc_price'] * $quantity);
        $tot += ($product['product_price'] * $quantity);
    }

    echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'></head>";
    echo "<br><p style='color:black'> <span class='mx-2'> Your total is: " . $dtot . "</span>";
    echo "<span class='fas fa-long-arrow-alt-left'> </span> <span style='text-decoration:line-through'> From : " . $tot . "</span></p><br>";

    echo "<form id='cart-update' method='post' action='update_cart.php' class='mt-4'>";
    // echo "<button type='submit' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full'>Update Cart</button>";
    echo "</form>";

    echo "<button onclick='buyItems()' class='mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full'>Buy Now</button>";
} else {
    echo "<p style='color : black;'>Your cart is empty.</p>";
}

echo "</div>";
?>



<script>
function buyItems() {
    // Assume there's a function buyItem in a PHP file which processes the purchase
    fetch('buy_items.php', {
        method: 'POST',
        body: new FormData(document.getElementById('cart-update')),
        credentials: 'include'
    }).then(response => {
        if (response.ok) {
            alert('Purchase successful!');
            location.reload(); // Reload the page to update the cart display
        } else {
            alert('Failed to process purchase. Please try again.');
        }
    }).catch(error => {
        console.log('Error:', error);
    });
}
</script>