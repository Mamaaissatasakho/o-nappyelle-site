<?php
session_start();
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=generativea", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier que l'ID est présent dans l'URL
    if (!isset($_GET['id'])) {
        die("ID d'inscription manquant.");
    }

    $id = $_GET['id'];

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $fonction = $_POST['fonction'] ?? '';
        $interets = $_POST['interets'] ?? '';
        $commentaire = $_POST['commentaire'] ?? '';

        // Mise à jour dans la base
        $stmt = $pdo->prepare("UPDATE inscriptions SET nom = ?, prenom = ?, email = ?, adresse = ?, telephone = ?, fonction = ?, interets = ?, commentaire = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $adresse, $telephone, $fonction, $interets, $commentaire, $id]);

        // Message flash
        $_SESSION['flash'] = "Modification réussie !";
        header("Location: admin.php");
        exit();
    }

    // Récupérer les données existantes
    $stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE id = ?");
    $stmt->execute([$id]);
    $inscription = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$inscription) {
        die("Inscription non trouvée.");
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'inscription - Ô Nappy'Elle</title>
    <meta name="description" content="Formulaire pour modifier une inscription existante.">
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

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff4e6;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }

        button {
            background-color: #d35400;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #c0392b;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Modifier l'inscription</h1>

    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($inscription['nom'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($inscription['prenom'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($inscription['email'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($inscription['adresse'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($inscription['telephone'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Fonction :</label>
        <select name="fonction" required>
            <?php
            $fonctions = ['Participant', 'Intervenant', 'Organisateur'];
            foreach ($fonctions as $f) {
                $selected = ($inscription['fonction'] === $f) ? 'selected' : '';
                echo "<option value=\"$f\" $selected>$f</option>";
            }
            ?>
        </select>

        <label>Intérêts :</label>
        <input type="text" name="interets" value="<?= htmlspecialchars($inscription['interets'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Commentaire :</label>
        <textarea name="commentaire"><?= htmlspecialchars($inscription['commentaire'], ENT_QUOTES, 'UTF-8') ?></textarea>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <div style="text-align:center;">
        <a href="admin.php">← Retour à la liste</a>
    </div>
</body>
</html>
