<?php
require_once 'exam-login.php'; //ger tillgång till filen som har tillgång till den aktuella databasen

try { 
    $pdo = new PDO($attr, $user, $pass, $opts); //skapar en ny PDO-instans som används för att ansluta till en databas
} catch (PDOException $e) { 
    throw new PDOException($e->getMessage(), (int)$e->getCode());//om ett fel skulle uppstå får man ett felmeddelande och en felkod
}

// Kollar om databasen finns
$tableExists = $pdo->query("SHOW TABLES LIKE 'users'")->rowCount() > 0;
//om databasen inte finns skapas en  genom att spara queryn i en variabel som  som sedan skickas till databasen
if (!$tableExists) { 
    $createTableQuery = "CREATE TABLE users ( 
        name VARCHAR(32) NOT NULL,
        password VARCHAR(64) NOT NULL,
        id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY) ENGINE InnoDB";

    try { //om det gick att skapa databasen
        $pdo->query($createTableQuery);
        echo "Table 'users' created successfully.<br>";
    } catch (PDOException $e) { //om det inte gick att skapa databasen med felmeddelande
        echo "Error creating table: " . $e->getMessage() . "<br>";
    }
} else { //om databasen redan finns
    echo "Table 'users' already exists.<br>";
}
//funktion för att lägag till användare i databasen
function add_user($pdo, $n, $pw) {
    $stmt = $pdo->prepare('INSERT INTO users (name, password) VALUES(?, ?)'); //En SQL-sats sparas i en variabel för att infoga en ny rad i tabellen förbereds. Två platshållare (?) används för användarnamnet och lösenordet för att öka säkerheten

    //Här bins de två platshållarna till användarnamn och lösenord samt definierar formatet som de ska ha och sparas i förberedda påståenden
    $stmt->bindParam(1, $n, PDO::PARAM_STR, 32);
    $stmt->bindParam(2, $pw, PDO::PARAM_STR, 64);

    try { //om det gick att lägga till användarna
        $stmt->execute(); //kör de förebredda påståendena
        echo "User '$n' added successfully.<br>";
    } catch (PDOException $e) { //fångar eventuella fel och skickar felmeddelande
        echo "Error adding user '$n': " . $e->getMessage() . "<br>";
    }
}

$name = 'Harry';
$password = 'Potter';
$hash = password_hash($password, PASSWORD_DEFAULT); //kör hash-funktionen på lösenordet så att det sparas i databasen på ett säkert sätt
add_user($pdo, $name, $hash);

$name = 'Ron';
$password = 'Weasley';
$hash = password_hash($password, PASSWORD_DEFAULT);
add_user($pdo, $name, $hash);
?>
