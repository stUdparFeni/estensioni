<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatrice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }
        .calculator {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input, select, button {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="calculator">
        <h1>Calcolatrice</h1>
        <form method="POST" action="">
            <input type="number" name="numero1" placeholder="Inserisci il primo numero" required>
            <input type="number" name="numero2" placeholder="Inserisci il secondo numero" required>
            <select name="operazione">
                <option value="somma">Somma</option>
                <option value="sottrazione">Sottrazione</option>
                <option value="moltiplicazione">Moltiplicazione</option>
                <option value="divisione">Divisione</option>
            </select>
            <button type="submit">Calcola</button>
        </form>

        <?php
        // Connessione al database SQLite
        $db = new SQLite3('calcolatrice.db');

        // Creazione della tabella se non esiste
        $db->exec("CREATE TABLE IF NOT EXISTS calcoli (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            numero1 REAL,
            numero2 REAL,
            operazione TEXT,
            risultato TEXT
        )");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $numero1 = $_POST['numero1'];
            $numero2 = $_POST['numero2'];
            $operazione = $_POST['operazione'];
            $risultato = '';

            // Calcolo
            switch ($operazione) {
                case 'somma':
                    $risultato = $numero1 + $numero2;
                    break;
                case 'sottrazione':
                    $risultato = $numero1 - $numero2;
                    break;
                case 'moltiplicazione':
                    $risultato = $numero1 * $numero2;
                    break;
                case 'divisione':
                    if ($numero2 != 0) {
                        $risultato = $numero1 / $numero2;
                    } else {
                        $risultato = 'Errore: Divisione per zero!';
                    }
                    break;
                default:
                    $risultato = 'Operazione non valida.';
                    break;
            }

            // Salvataggio nel database
            $stmt = $db->prepare("INSERT INTO calcoli (numero1, numero2, operazione, risultato) VALUES (:numero1, :numero2, :operazione, :risultato)");
            $stmt->bindValue(':numero1', $numero1, SQLITE3_FLOAT);
            $stmt->bindValue(':numero2', $numero2, SQLITE3_FLOAT);
            $stmt->bindValue(':operazione', $operazione, SQLITE3_TEXT);
            $stmt->bindValue(':risultato', $risultato, SQLITE3_TEXT);
            $stmt->execute();

            echo "<div class='result'>Risultato: $risultato</div>";
        }

        // Mostra lo storico dei calcoli
        $result = $db->query("SELECT * FROM calcoli ORDER BY id DESC LIMIT 10");
        echo "<h2>Storico Calcoli</h2><ul>";
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<li>{$row['numero1']} {$row['operazione']} {$row['numero2']} = {$row['risultato']}</li>";
        }
        echo "</ul>";
        ?>
    </div>
</body>
</html>
