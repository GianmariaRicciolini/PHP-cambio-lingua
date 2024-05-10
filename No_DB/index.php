<?php
// Funzione per impostare il cookie della lingua
function setLanguageCookie($language) {
    setcookie('lingua', $language, time() + (365 * 24 * 60 * 60), '/'); // 1 anno di durata del cookie
}

// Imposta la lingua predefinita
$lingua_predefinita = 'it';
$lingua = isset($_COOKIE['lingua']) ? $_COOKIE['lingua'] : $lingua_predefinita;

// Funzione per ottenere il testo in base alla lingua
function getTesto($lingua) {
    switch ($lingua) {
        case 'en':
            return 'Good Morning';
        case 'fr':
            return 'Bonjour';
        default:
            return 'Buongiorno';
    }
}

// Se è stato selezionato un cambio di lingua, imposta il cookie
if (isset($_GET['lingua'])) {
    $nuova_lingua = $_GET['lingua'];
    setLanguageCookie($nuova_lingua);
    $lingua = $nuova_lingua;
}

// Imposta il tipo di contenuto della pagina
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="<?php echo $lingua; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Multilingua</title>
</head>
<body>
    <h1><?php echo getTesto($lingua); ?></h1>
    <header>
        <nav>
            <ul>
                <li><a href="?lingua=it">Italiano</a></li>
                <li><a href="?lingua=en">English</a></li>
                <li><a href="?lingua=fr">Français</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
