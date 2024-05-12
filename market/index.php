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
        <a href="./index.php">Market</a>
        <a href="./profile.php">Profile</a>
    </div>
    <div class="add-button-container">
        <a href="add_product.php" class="btn add-new">Add New Product</a>
    </div>
    <table>
        <tr>
            <th>Title</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Discounted Price</th>
            <th>Expiration Date</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        for ($i = 0; $i < 10; $i++) {
            echo "<tr>";
            echo "<td>Title</td>";
            echo "<td>Stock</td>";
            echo "<td>Price</td>";
            echo "<td>Discounted Price</td>";
            echo "<td>Expiration Date</td>";
            echo "<td><a href='edit.php?id=$i' class='btn edit'>Edit</a></td>";
            echo "<td><a href='delete.php?id=$i' class='btn delete'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
