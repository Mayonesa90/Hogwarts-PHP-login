
<?php

session_start(); //startar session

if(isset($_SESSION['name'])){ //kollar om namn finns i session
    $n = $_SESSION['name']; //sparar namnet i varabel

    destroy_session_and_data(); //sätter igång funktion som avslutar sessionen så att om man startar om sidan är man utloggad

    echo <<<_END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam project - signed in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="logo.svg" alt="" class="logo">
    <button class="signout-btn" onClick="window.location.reload()">SIGN OUT</button>
    <div class="message">
        Welcome $n...<br>
        It's good to see ya
    </div>
    <img src="albus.svg" alt="" class="albus">
_END;
        
} else {
    echo <<<_END
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam project - signed out</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="logo.svg" alt="" class="logo">
        <div class="message-signedout">You've been signed out.<br>
        Please <a href='login.php'>click here</a> to go to the login page.
        </div>
_END;
}


function destroy_session_and_data(){
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/'); //sätter tiden för att avsluta session bakåt i tiden så den avslutas på en gång
    session_destroy();
}

echo <<<_END
    </body>
    </html>
_END;
?>