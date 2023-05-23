<?php
$host = 'your_host';
$dbName = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

$pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);

function getProduct($productId, $pdo)
{
    $sql = "SELECT * FROM products WHERE id = :productId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getReviews($productId, $pdo)
{
    $sql = "SELECT * FROM reviews WHERE product_id = :productId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addReview($productId, $userName, $rating, $comment, $pdo)
{
    $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (:productId, :userName, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':productId', $productId);
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
}

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    $product = getProduct($productId, $pdo);

    if ($product) {
        $reviews = getReviews($productId, $pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['user_name'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];

            addReview($productId, $userName, $rating, $comment, $pdo);

            header("Location: product.php?product_id=$productId");
            exit;
        }
    } else {
        echo 'Product not found.';
        exit;
    }
} else {
    // Product ID not provided in the URL, handle error or redirect
    echo 'Product ID not provided.';
    exit;
}
?>
