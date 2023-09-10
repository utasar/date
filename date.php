
$date = $_POST['date'];

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO dates (date) VALUES (?)");
$stmt->bind_param("s", $date);
$stmt->execute();
 
$conn->close();
header("Location: success.php");
