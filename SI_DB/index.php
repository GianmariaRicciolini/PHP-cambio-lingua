<?php
// Connessione al database (sostituisci con i tuoi dati di connessione)
$host = 'localhost';
$db   = 'ifoa_lang_coockie';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Comando che connette al database
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}

// Imposta la lingua predefinita
$lingua_predefinita = 'it';
$lingua = isset($_COOKIE['lingua']) ? $_COOKIE['lingua'] : $lingua_predefinita;

// Se è stato selezionato un cambio di lingua, imposta il cookie
if (isset($_GET['lingua'])) {
    $nuova_lingua = $_GET['lingua'];
    setcookie('lingua', $nuova_lingua, time() + (365 * 24 * 60 * 60), '/');
    $lingua = $nuova_lingua;
}

// Ottieni le lingue disponibili
$sql_lingue = "SELECT codice_lingua, nome_lingua FROM lingue";
$stmt_lingue = $pdo->query($sql_lingue);
$lingue_disponibili = $stmt_lingue->fetchAll(PDO::FETCH_ASSOC);

// Ottieni il titolo e il testo dell'articolo nella lingua selezionata
$sql = "SELECT titolo, contenuto FROM news WHERE id_lingua = (SELECT id_lingua FROM lingue WHERE codice_lingua = ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$lingua]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $titolo = $row['titolo'];
    $testo = $row['contenuto'];
} else {
    // Se non ci sono risultati nel database, utilizza un fallback
    $titolo = "Titolo dell'articolo";
    $testo = "Contenuto dell'articolo";
}

// Chiudi la connessione al database
$pdo = null;
?>

<!DOCTYPE html>
<html lang="<?php echo $lingua; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Multilingua</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php
            // Genera i link per cambiare lingua utilizzando i dati del database
            if ($lingue_disponibili) {
                foreach ($lingue_disponibili as $lingua) {
                    $codice_lingua = $lingua['codice_lingua'];
                    $url = htmlspecialchars($_SERVER['PHP_SELF']) . "?lingua=$codice_lingua";
                    echo "<li class='nav-item'><a class='nav-link' href='$url'>$codice_lingua</a></li>";
                }
            } else {
                echo "<li class='nav-item'><a class='nav-link' href='#'>Lingue non disponibili</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1><?php echo $titolo ?? ''; ?></h1>
    <p><?php echo $testo ?? ''; ?></p>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>