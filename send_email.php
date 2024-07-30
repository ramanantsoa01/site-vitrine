<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'adresse email depuis le formulaire
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // Valider l'email (vous pouvez ajouter une validation plus stricte ici)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email non valide.");
    }

    // Chemin vers le fichier PDF à envoyer
    $file_path = './ebook/benoit_les_compagnons_d_ulysse.pdf';

    // Vérifier si le fichier existe
    if (!file_exists($file_path)) {
        die("Fichier PDF non trouvé.");
    }

    // Configuration pour l'email
    $to = $email;
    $from = 'testdev280@gmail.com';
    $subject = 'Votre Ebook Gratuit';
    $message = 'Merci d\'avoir téléchargé notre Ebook sur les démarches administratives à Madagascar.';

    // Headers pour l'email
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: application/pdf\r\n";
    $headers .= "Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"\r\n";

    // Lire le contenu du fichier PDF
    $content = file_get_contents($file_path);

    // Ajouter le fichier PDF en pièce jointe
    $attachment = chunk_split(base64_encode($content));

    // Ajouter la pièce jointe à l'email
    $message .= "--PHP-mixed-" . md5(time()) . "\r\n";
    $message .= "Content-Type: application/pdf; name=\"" . basename($file_path) . "\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment\r\n";
    $message .= "\r\n" . $attachment . "\r\n";

    // Envoyer l'email
    if (mail($to, $subject, $message, $headers)) {
        // Réponse JSON pour indiquer que l'email a été envoyé avec succès
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        exit();
    } else {
        // Réponse JSON en cas d'échec de l'envoi de l'email
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'message' => 'Erreur lors de l\'envoi de l\'email.'));
        exit();
    }
}
?>
