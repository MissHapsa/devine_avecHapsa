<?php

/*
    Créez un petit jeu en PHP :

    Le jeu du plus ou moins.

    Le serveur génère un nombre aléatoire compris entre 1 et 100.
    L'utilisateur doit trouver le nombre.
    Si le nombre est plus grand que celui entré, le serveur
    indique que le nombre est trop grand.

    L'utilisateur est invité à entrer un nombre.

    Le serveur indique si le nombre entré est inférieur ou
    supérieur au sien.

    Une fois que l'utilisateur a trouvé le nombre, un message
    est affiché, indiquant le nombre de tentatives qu'il aura
    fallu.

    On créera une fonction `testerNombre($nombreUtilisateur, $nombreATrouver)`
    qui renverra trois valeurs différentes, selon le cas :
        1 : le nombre entré est encore trop petit
        -1 : le nombre entré est encore trop grand
        0 : le nombre entré est correct

    Pour obtenir un nombre aléatoire en PHP, cherchez dans la 
    documentation la fonction appropriée.


    Deuxième étape :
    - Filtrez et nettoyez les saisies utilisateur :
        on attend rien d'autre qu'un nombre.
    - Faites en sorte qu'on puisse voir tous les essais déjà réalisés, ainsi que le message "trop grand" ou "trop petit" à côté de chaque essai.

        On pourra utiliser des tableaux d'éléments HTML dans le formulaire :
        <input type="hidden" name="mon-tableau[]" value="Première valeur" />
        <input type="hidden" name="mon-tableau[]" value="Deuxième valeur" />
        <input type="hidden" name="mon-tableau[]" value="Troisième valeur" />

        => Ces éléments, placés dans un formulaire envoyé en POST, seront reçues ainsi par PHP :
        $_POST['mon-tableau'] => array(
            0 => "Première valeur"
            1 => "Deuxième valeur"
            2 => "Troisième valeur"
        )

    - Affichez le temps écoulé entre le début et la fin de la partie.

*/
?>
<?php
session_start();

// Fonction de nettoyage pour assurer que l'input est un nombre entier
function cleanInput($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
}

// Vérifie si la variable de session existe
if (!isset($_SESSION['numberToGuess'])) {
    // Si elle n'existe pas, génère un nombre aléatoire entre 1 et 100
    $_SESSION['numberToGuess'] = rand(1, 100);
    $_SESSION['attempts'] = 0; // Initialise le compteur d'essais
    $_SESSION['startTime'] = time(); // Enregistre le temps de début
    $_SESSION['guesses'] = array(); // Initialise le tableau des essais
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
    $userGuess = cleanInput($_POST['guess']);
    $_SESSION['attempts']++; // Incrémente le compteur d'essais

    // Ajoute la tentative au tableau des essais
    $_SESSION['guesses'][] = $userGuess;

    if ($userGuess > $_SESSION['numberToGuess']) {
        $message = "Le nombre est trop grand.";
    } elseif ($userGuess < $_SESSION['numberToGuess']) {
        $message = "Le nombre est trop petit.";
    } else {
        $endTime = time(); // Enregistre le temps de fin
        $elapsedTime = $endTime - $_SESSION['startTime'];

        $message = "Félicitations, vous avez deviné le nombre en " . $_SESSION['attempts'] . " essais.";
        $message .= "<br>Temps écoulé : " . $elapsedTime . " secondes";

        // Réinitialise la variable de session pour un nouveau jeu
        unset($_SESSION['numberToGuess']);
        unset($_SESSION['attempts']);
        unset($_SESSION['startTime']);
        unset($_SESSION['guesses']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEVINE AVEC HAPSA</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        h1, p {
            color: #e73a76;
            
        }

        p {
            font-size: 32px;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 18px;
            color: #333;
        }

        input {
            padding: 8px;
            font-size: 16px;
        }

        button {
            background-color: #e73a76;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        button.reset {
            background-color: #ff9800;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            font-size: 16px;
            margin-bottom: 5px;
        }

        a {
            text-decoration: none;
        }

        button:hover, button.reset:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>DEVINE AVEC HAPSA</h1>
    <p>Devine le juste prix entre 1 et 100.</p>

    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['guesses'])) : ?>
        <h2>Essais réalisés :</h2>
        <ul>
            <?php foreach ($_SESSION['guesses'] as $attempt) : ?>
                <li><?php echo $attempt; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label for="guess">Entrez votre estimation :</label>
        <input type="number" name="guess" id="guess" required min="1" max="100">
        <button type="submit">Soumettre</button>
        <a href="reset.php"><button type="button">Réinitialiser</button></a>   
    </form>
</body>
</html>
