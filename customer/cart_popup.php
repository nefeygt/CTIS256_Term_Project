<?php
// Initialize the cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

echo "<div class='max-w-lg mx-auto bg-white p-4 shadow rounded'>";

// Display the cart items
if (!empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) {
            $maxQuantity = min($product['stock'], $quantity); // limit max quantity to the stock available
            echo "<li class='flex justify-between items-center p-2 border-b border-gray-200'>";
            echo htmlspecialchars($product['product_title']);
            echo "<input type='number' name='quantity[$productId]' value='" . htmlspecialchars($quantity) . "' 
                   class='w-16 text-center border-gray-300 rounded p-1' min='1' max='$maxQuantity' form='cart-update'>";
            echo "<a href='remove_from_cart.php?product_id=" . urlencode($productId) . "' class='ml-4 text-red-500 hover:text-red-700'>Remove</a>";
            echo "</li>";
        }
    }
    echo "</ul>";
    echo "<form id='cart-update' method='post' action='update_cart.php' class='mt-4'>";
    echo "<button type='submit' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full'>Update Cart</button>";
    echo "</form>";
    echo "<button onclick='buyItems()' class='mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full'>Buy Now</button>";
} else {
    echo "Your cart is empty.";
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