<?php
session_start();

// Réinitialise la variable de session pour un nouveau jeu
unset($_SESSION['numberToGuess']);
unset($_SESSION['attempts']);
unset($_SESSION['startTime']);
unset($_SESSION['guesses']);

// Redirige vers la page principale
header("Location: index.php");
exit();
?>
