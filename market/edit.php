<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Godzilla</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="./index.php">Market</a>
        <a href="./profile.php">Profile</a>
    </div>
    <div class="form-container">
        <h2>Edit Product</h2>
        <form action="update_product.php" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>
            <label for="discounted_price">Discounted Price:</label>
            <input type="text" id="discounted_price" name="discounted_price">
            <label for="expiration_date">Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">  <!-- Pass the product ID to the update script -->
            <button type="submit" class="btn edit">Update Product</button>
        </form>
    </div>
</body>
</html>
