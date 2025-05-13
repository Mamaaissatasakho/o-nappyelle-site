<?php
session_start();
try {
    $pdo = new PDO("mysql:host=localhost;dbname=generativea", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Suppression d'une inscription
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM inscriptions WHERE id = ?");
        $stmt->execute([$id]);
    
        $_SESSION['flash'] = "Inscription supprimée avec succès !";
        header("Location: admin.php");
        exit();
    }
    

    // Récupération des inscriptions
    $stmt = $pdo->query("SELECT * FROM inscriptions ORDER BY id DESC");
    $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Interface d'administration des inscriptions à l'événement Ô Nappy'Elle.">
    <title>Ô Nappy'Elle - Admin | Liste des Inscriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #d35400;
            color: white;
        }

        td {
            background-color: #fff4e6;
        }

        a {
            color: #c0392b;
            text-decoration: none;
            margin: 0 5px;
        }

        a:hover {
            text-decoration: underline;
        }

        .cta {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Liste des Inscriptions</h1>
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 20px auto; width: 80%; border: 1px solid #c3e6cb; border-radius: 5px;">
            <?= htmlspecialchars($_SESSION['flash'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>


    <?php if (count($inscriptions) === 0): ?>
        <p>Aucune inscription pour le moment.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Fonction</th>
                <th>Intérêts</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($inscriptions as $inscription): ?>
                <tr>
                    <td><?= htmlspecialchars($inscription['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['adresse'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['fonction'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['interets'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($inscription['commentaire'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="details.php?id=<?= $inscription['id'] ?>">Détails</a>
                        <a href="modifier.php?id=<?= $inscription['id'] ?>">Modifier</a>
                        <a href="admin.php?delete=<?= $inscription['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <div class="cta">
        <a href="accueil.html">Retour à l'accueil</a>
    </div>
</body>
</html>
