<?php
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=generativea", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si un ID est passé dans l'URL
    if (!isset($_GET['id'])) {
        die("Aucun ID fourni.");
    }

    $id = $_GET['id'];

    // Requête pour récupérer l'inscription
    $stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE id = ?");
    $stmt->execute([$id]);
    $inscription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$inscription) {
        die("Inscription introuvable.");
    }

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Détails d'une inscription à l'événement Ô Nappy'Elle.">
    <title>Détails de l'inscription - Ô Nappy'Elle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffdf9;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #d35400;
        }

        .details {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff4e6;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .details p {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            color: #c0392b;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Détails de l'inscription</h1>

    <div class="details">
        <p><span class="label">Nom :</span> <?= htmlspecialchars($inscription['nom'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Prénom :</span> <?= htmlspecialchars($inscription['prenom'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Email :</span> <?= htmlspecialchars($inscription['email'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Adresse :</span> <?= htmlspecialchars($inscription['adresse'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Téléphone :</span> <?= htmlspecialchars($inscription['telephone'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Fonction :</span> <?= htmlspecialchars($inscription['fonction'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Intérêts :</span> <?= htmlspecialchars($inscription['interets'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><span class="label">Commentaire :</span> <?= htmlspecialchars($inscription['commentaire'], ENT_QUOTES, 'UTF-8') ?></p>

        <a href="admin.php">← Retour à la liste</a>
    </div>
</body>
</html>
