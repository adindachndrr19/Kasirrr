<?php
// Tambahkan header CORS
header('Access-Control-Allow-Origin: http://localhost:3000'); // Izinkan semua origin
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Izinkan header tertentu

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "kasir";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM items";
$result = $conn->query($sql);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($items);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents("php://input"), true);
  error_log("Data received: " . print_r($data, true));

  $merk = $data['merk'];
  $price = $data['price'];

  $sql = "INSERT INTO items (merk, price) VALUES ('$merk', '$price')";
  if ($conn->query($sql) === TRUE) {
      echo json_encode(["message" => "Item added successfully"]);
  } else {
      echo json_encode(["error" => "Error: " . $conn->error]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  $data = json_decode(file_get_contents("php://input"), true);
  error_log(print_r($data, true));

  $id_produk = $data['id_produk'];
  $merk = $data['merk'];
  $price = $data['price'];

  $sql = "UPDATE items SET merk='$merk', price='$price' WHERE id_produk='$id_produk'";
  if ($conn->query($sql) === TRUE) {
      echo json_encode(["message" => "Item updated successfully"]);
  } else {
      error_log("Database error: " . $conn->error);
      echo json_encode(["error" => "Error: " . $conn->error]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  $data = json_decode(file_get_contents("php://input"), true);

  $id_produk = $data['id_produk'];

  $sql = "DELETE FROM items WHERE id_produk='$id_produk'";
  if ($conn->query($sql) === TRUE) {
      echo json_encode(["message" => "Item deleted successfully"]);
  } else {
      echo json_encode(["error" => "Error: " . $conn->error]);
  }
  exit;
}

?>
