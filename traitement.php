<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Connexion à la base
try {
    $pdo = new PDO("mysql:host=localhost;dbname=generativea;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div style='color:red; font-family:Arial;'>Erreur de connexion à la base de données : " . $e->getMessage() . "</div>");
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des champs
    $nom       = $_POST["nom"] ?? '';
    $prenom    = $_POST["prenom"] ?? '';
    $email     = $_POST["email"] ?? '';
    $adresse   = $_POST["adresse"] ?? '';
    $telephone = $_POST["telephone"] ?? '';
    $fonction  = $_POST["fonction"] ?? '';
    $interets  = $_POST["interet"] ?? '';
    $commentaire = $_POST["commentaire"] ?? '';

    // Insertion en base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO inscriptions (nom, prenom, email, adresse, telephone, fonction, interets, commentaire)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $adresse, $telephone, $fonction, $interets, $commentaire]);

        // Envoi de l'e-mail de confirmation
        require 'PHPMailer-master/src/Exception.php';
        require 'PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sakhotatus@gmail.com';               // ton adresse Gmail
            $mail->Password   = 'jskk lqvh agfl krjm';                // mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('sakhotatus@gmail.com', "Ô Nappy'Elle");
            $mail->addAddress($email, "$prenom $nom");

            $mail->isHTML(true);
            $mail->Subject = 'Confirmation d\'inscription - Ô Nappy\'Elle';
            $mail->Body    = "
                <h3>Bonjour $prenom,</h3>
                <p>Merci pour votre inscription à la cérémonie de lancement de <strong>Ô Nappy'Elle</strong>.</p>
                <p>Nous avons bien reçu vos informations :</p>
                <ul>
                    <li><strong>Nom :</strong> $nom</li>
                    <li><strong>Adresse :</strong> $adresse</li>
                    <li><strong>Téléphone :</strong> $telephone</li>
                    <li><strong>Fonction :</strong> $fonction</li>
                    <li><strong>Centres d’intérêt :</strong> $interets</li>
                </ul>
                <p>Nous avons hâte de vous rencontrer !</p>
                <p><em>L'équipe Ô Nappy'Elle</em></p>
            ";

            $mail->send();

            // Message de confirmation en PHP (optionnel, car HTML affiché ensuite)
            echo "<div style='color:green;'>E-mail de confirmation envoyé à $email.</div>";

        } catch (Exception $e) {
            echo "<div style='color:red;'>Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}</div>";
        }

    } catch (PDOException $e) {
        die("<div style='color:red;'>Erreur lors de l'insertion : " . $e->getMessage() . "</div>");
    }
} else {
    die("<div style='color:red;'>Formulaire non soumis.</div>");
}
?>

<!-- Affichage HTML de confirmation -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            padding: 40px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h2 {
            color: #27AE60;
            text-align: center;
        }
        p {
            font-size: 16px;
            text-align: center;
        }
        a {
            display: block;
            margin-top: 20px;
            color: #2980B9;
            text-align: center;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Merci pour votre inscription !</h2>
    <p>Votre inscription a bien été enregistrée.</p>
    <p>Un e-mail de confirmation vous a été envoyé à <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
    <a href="admin.php">Voir la liste des inscriptions</a>
    <a href="accueil.html">Retour à l'accueil</a>
</div>
</body>
</html>
