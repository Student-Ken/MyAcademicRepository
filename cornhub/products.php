<?php
session_start();
include("include/connection.php");

$user_ID = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : null;

$sql = "SELECT * FROM product";
$all_product = $conn->query($sql);

//Function for add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['prod_id'];
    $product_name = $_POST['prod_name'];
    $product_price = $_POST['prod_price'];
    $product_image = $_POST['prod_img'];
    $product_quantity = 1;

    $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_ID = ? AND product_ID = ?");
    $select_cart->bind_param("ii", $user_ID, $product_id);
    $select_cart->execute();
    $result = $select_cart->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Product already added to cart.');</script>";
    } else {
        $insert_product = $conn->prepare("INSERT INTO cart (user_ID, product_ID, prod_name, prod_price, prod_img, prod_qty) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_product->bind_param("iisdsi", $user_ID, $product_id, $product_name, $product_price, $product_image, $product_quantity);

        if ($insert_product->execute()) {
            echo "<script>alert('Product added to cart successfully.');</script>";
        } else {
            echo "<script>alert('Failed to add product to cart.');</script>";
        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homepage</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="prodstyle.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Racing+Sans+One&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        <?php
            if(isset($message)){
                foreach($message as $message){
                    echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = none;"></i> </div>';
                };
            };
        ?>
        <?php require_once('include/navbar2.php'); ?>
        <main>
            <!--Fetch all the products and diplay it-->
            <?php
                while($row = mysqli_fetch_assoc($all_product)){
            ?>
            <form action="" method="post">
                <div class="card" style="width: 18rem;">
                    <div class="card-image">
                        <img src="<?php echo $row["prod_img"]; ?>" class="card-img-top rounded" alt="...">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h5 class="product_name"><?php echo $row["prod_name"]; ?></h5>
                            </div>
                            <div class="col-4 ms-auto">
                                <p class="price">₱<?php echo $row["prod_price"]; ?></p>
                            </div>
                        </div>
                        <p class="desc"><?php echo $row["prod_desc"]; ?></p>
                        <input type="hidden" name="prod_id" value="<?php echo $row["product_ID"]; ?>">
                        <input type="hidden" name="prod_name" value="<?php echo $row["prod_name"]; ?>">
                        <input type="hidden" name="prod_price" value="<?php echo $row["prod_price"]; ?>">
                        <input type="hidden" name="prod_img" value="<?php echo $row["prod_img"]; ?>">
                        <button class="btn rounded" type="submit" value="add to cart" name="add_to_cart">Add to Cart</button>
                    </div>
                </div>
            </form>
            <?php
                }
            ?>
        </main>
        <?php require_once('include/footer2.php'); ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
    </html>