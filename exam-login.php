
<?php
require_once 'vendor/autoload.php'; // Use dotenv library

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Enable error reporting in your PHP script (login.php)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get sensitive data from environment variables
$host = $_ENV['DB_HOST'];
$data = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$chrs = $_ENV['DB_CHARSET'];

// Check if environment variables are loaded correctly
if (!$host || !$data || !$user || !$pass || !$chrs) {
    echo "Error: One or more environment variables are missing.";
    exit;
}

$opts =
[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //ställer in PDO att kasta undantag vid SQL-fel
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //ställer in standardläget för att hämta data från databasen till att returnera associerade arrayer 
    PDO::ATTR_EMULATE_PREPARES => false, // gör så att förberedda uttalanden används om databasen stöder det
];

// Create a DSN (Data Source Name) string
$dsn = "mysql:host=$host;dbname=$data;charset=$chrs";

// Attempt to establish a PDO connection
try {
    $pdo = new PDO($dsn, $user, $pass, $opts);
    echo 'Connected to the database successfully!';
} catch (PDOException $e) {
    // If connection fails, show the error
    echo 'Connection failed: ' . $e->getMessage();
}
?>