<?php

require_once 'exam-login.php'; //ger tillgång till filen som har tillgång till den aktuella databasen

// Define the DSN (Data Source Name) string for PDO
$dsn = "mysql:host=$host;dbname=$data;charset=$chrs"; 
// PDO options
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_EMULATE_PREPARES => false,
];

try { 
    // $pdo = new PDO($attr, $user, $pass, $opts); //skapar en ny PDO-instans som används för att ansluta till en databas
    $pdo = new PDO($dsn, $user, $pass, $opts); 
} catch (PDOException $e) { 
    throw new PDOException($e->getMessage(), (int)$e->getCode());//om ett fel skulle uppstå får man ett felmeddelande och en felkod
}

echo <<<_END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam project - authenticate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="logo.svg" alt="" class="logo">
    <form action="login.php" method="post">
        <label for="name" class="label">Name: 
            <input type="text" id="name" name="name">
        </label><br>
        <label for="password" class="label">Password: 
            <input type="password" id="password" name="password">
        </label><br>
        <input type="submit" value="SIGN IN" class="label signin-btn">

_END;

if(isset($_POST['name']) && isset($_POST['password'])){ //kollar om båda inputfälten har värde
    $n_temp = sanitize($pdo, $_POST['name']); //skickar namnet till funktionen sanitize() som gör strängen ofarlig och sparar i en temporär variabel
    $pw_temp = sanitize($pdo, $_POST['password']); //skickar lösenordet till funktionen sanitize() som gör strängen ofarlig och sparar i en temporär variabel
    $query = "SELECT * FROM users WHERE name=$n_temp"; //sparar förfrågan om namnet finns i databasen i en variabel
    $result = $pdo->query($query); //sparar svaret i variabel efter att förfrågan skickats till databasen

    if (!$result->rowCount()) { //om det inte blev en träff i databasen
        echo "<p>User not found</p>";
    } else { //om det blev en träff i databasen
        $row = $result->fetch(); //hämtar datan och sparar i variabel
        $n = $row['name']; //sparar namnet från databsen i variabel från resultatet
        $pw = $row['password']; //sparar lösenordet från databasen i variabel från resultatet

        if (password_verify(str_replace("'", "", $pw_temp), $pw)) { //kollar om det temporära lösenordet stämmer med det riktiga 
            session_start(); //startar sessionen
            $_SESSION['name'] = $n; //sparar namnet i sessionen
            echo "<p>Hi <strong>$n</strong>, you are now logged in!</p><br>"; //skickar svar till användaren
            echo "<p><a href='gryffindor.php'>Click here to continue</a></p>"; //skickar en länk att fortsätta till
        } else {
            echo "<p>Invalid username/password combination</p>"; //svar om namn och lösenordskombination inte stämmer
        }
    }
}

echo <<<_END
</form>
</body>
</html>
_END;

function sanitize($pdo, $str){ //funktion som saniterar strings
    $str = htmlentities($str); //htmlenteties() är en inbyggd funktion som konverterar alla applicerbara karaktärer till självständig HTML, funktionen sparar sen resultatet i en variabel
    return $pdo->quote($str); //kallar på pdo-objektet med quote() funktionen som sätter citattecken runt namnet och lösenordet
}

?>


