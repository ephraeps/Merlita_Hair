<?php
session_start();
$cart = $_SESSION['cart'];

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require 'db.php';

// Mapping of communes to delivery prices
$commune_prices = [
    'Ngaliema' => 6500,
    'Gombe' => 8000,
    'Limete' => 4000,
    'Bandalungwa' => 10000,
    'Barumbu' => 9000,
    'Kinshasa' => 8000,
    'Kasa Vubu' => 7000,
    'Lingwala' => 8000,
    'Kalamu' => 8000,
    'Ngiri ngiri' => 8500,
    'Lemba' => 7000,
    'Bandal' => 8000,
    'Ngaba' => 10000,
    'Mont Ngafula' => 6500,
    'UPN' => 6000,
    'Matete' => 9000,
    'Mbundi' => 5000,
    'Kitambo' => 6000,
    'Selembao' => 7000
];

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
//Loading the French version
$mail->setLanguage('fr', '/optional/path/to/language/directory/');
         
        // Get email from form
        $user_fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : null;
        $user_email = isset($_POST['email']) ? trim($_POST['email']) : null;
        $user_city = isset($_POST['city']) ? trim($_POST['city']) : null;
        $user_commune = isset($_POST['commune']) ? trim($_POST['commune']) : null;
        $user_quartier = isset($_POST['quartier']) ? trim($_POST['quartier']) : null;
        $user_avenue = isset($_POST['avenue']) ? trim($_POST['avenue']) : null;
        $user_reference = isset($_POST['reference']) ? trim($_POST['reference']) : null;
        $user_number = isset($_POST['number']) ? trim($_POST['number']) : null;
        $delivering_date = isset($_POST['date']) ? trim($_POST['date']) : null;

        // Calculate delivery price based on commune
        $delivery_price = isset($commune_prices[$user_commune]) ? $commune_prices[$user_commune] : 0;
        
        // Calculate cart total
        $cart_total = 0;
        if (isset($cart) && is_array($cart)) {
            foreach ($cart as $item) {
                if (isset($item['quantity'], $item['price'])) {
                    $cart_total += $item['quantity'] * $item['price'];
                }
            }
        }
        $grand_total = $cart_total + $delivery_price;

        if (!$user_email || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            echo "Veuillez fournir une adresse email valide.";
        } else {
            try {
                //Server settings
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'support@merlitahair.com';                     //SMTP username
                $mail->Password   = '2025Azerjik_';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('support@merlitahair.com', 'MerlitaHair Support');
                $mail->addAddress($user_email);                              //Add recipient from form
                $mail->addReplyTo('support@merlitahair.com');
                $mail->addAddress('support@merlitahair.com');

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Facture de votre commande chez Merlita_Hair';
                
                // Créer le contenu HTML avec bannière
                $mail->Body = ' 
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif;color: #111; }
                .banner { background: linear-gradient(135deg, #1a1f35 0%, #2a2f4a 100%); color: #f5f1e8; padding: 20px; text-align: center; }
                .banner h1 { margin: 0; font-size: 28px; font-family: "Dancing Script", cursive; }
                .banner p { margin: 10px 0 0 0; font-size: 14px; }
                .container { padding: 20px; }
                .client_info { background-color: #f9f9f9; padding: 20px; margin: 20px 0; border: 2px solid #e8747e; border-radius: 8px; font-weight: normal; }
                .client_info h3 { color: #1a1f35; margin-top: 0; }
                .client_info p { margin: 8px 0; color: #1a1f35; }
                .footer { background-color: #f5f1e8; padding: 15px; text-align: center; font-size: 12px; color: #1a1f35; border-top: 2px solid #e8747e; }
                .order { color: #000; padding: 20px; margin: 20px 0; border: 2px solid #e8747e; border-radius: 8px; }
                .order h3 { color: #1a1f35; }
                .head_content { padding: 20px; color: #1a1f35; line-height: 1.6; }
                table { background: white; }
                th { background-color: rgba(232, 116, 126, 0.15); color: #1a1f35; border-bottom: 2px solid #e8747e; }
                .total-row { background-color: #f5f1e8; font-weight: bold; color: #1a1f35; }
            </style>
        </head>
        <body>
            <div class="banner">
                <h1>Merlita_Hair</h1>
                <p>Votre facture</p>
            </div>
            
            <div class="head_content">
                <p>Bonjour,</p>
                <p> Merci infiniment pour votre commande chez <strong>Merlita_Hair</strong>.</p>
                <p>Nous sommes ravis de faire parti de votre expérience beauté et nous avons hâte que vous découvriez vos nouveaux produits.</p>
                <p>Chez <strong>Merlita_Hair</strong>, notre mission est de vous offrir des produits de qualité qui subliment vos cheveux et renforcent votre confiance au quotidien.</p>
                <p>Si vous avez la moindre question ou besoin d\'assistance, notre équipe reste à votre entière disposition.</p>
                <p>Merci encore pour votre confiance et bienvenue dans l\'univers <strong>Merlita_hair</strong></p>
            </div>

            <div class="client_info">
                <h3>Informations sur le client :</h3>
                <p><strong>Nom:</strong> ' . htmlspecialchars($user_fullname) . '</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($user_email) . '</p>
                <p><strong>Ville:</strong> ' . htmlspecialchars($user_city) . '</p>   
                <p><strong>Commune:</strong> ' . htmlspecialchars($user_commune) . '</p>
                <p><strong>Quartier:</strong> ' . htmlspecialchars($user_quartier) . '</p>
                <p><strong>Avenue:</strong> ' . htmlspecialchars($user_avenue) . '</p>
                <p><strong>Référence:</strong> ' . htmlspecialchars($user_reference) . '</p>
                <p><strong>Numéro de téléphone:</strong> ' . htmlspecialchars($user_number) . '</p>
                <p><strong>Date de livraison:</strong> ' . htmlspecialchars($delivering_date) . '</p>
            </div>

            <div class="To_do">
                <p><strong>IMPORTANT – Étapes pour récupérer votre commande :</strong></p>
                <ol>
                    <li>Veuillez conserver ce message, il sera nécessaire pour le retrait de votre commande.</li>
                    <li>Attendez l\'appel de notre service client, qui vous contactera sur le numéro indiqué sur votre facture afin de confirmer certaines informations.</li>
                    <li>À son arrivée, vous devrez présenter votre facture(ce message) au livreur pour pouvoir retirer votre commande.
                    <li>Effectuez le paiement auprès du livreur.</li>
                    <li>Récupérez ensuite votre commande auprès du livreur.</li>
                </ol>
                <p><strong>Merci pour votre confiance.</strong></p>
            </div>

            <div class="order">
                <h3>Détails de la commande :</h3>
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Produit</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantité</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Prix unitaire</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total</th>
                    </tr>' . 
                    (isset($cart) && is_array($cart) ? implode('', array_map(function($item) {
                        $product_name = isset($item['name']) ? htmlspecialchars($item['name']) : 'N/A';
                        $quantity = isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 0;
                        $price = isset($item['price']) ? number_format($item['price'], 0, ',', ' ') : '0';
                        $total = isset($item['quantity'], $item['price']) ? number_format($item['quantity'] * $item['price'], 0, ',', ' ') : '0';

                        return '<tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . $product_name . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . $quantity . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . $price . ' FC</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . $total . ' FC</td>
                        </tr>';
                    }, $cart)) : '') . '
                    <tr class="total-row">
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>Sous-total:</strong></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><strong>' . number_format($cart_total, 0, ',', ' ') . ' FC</strong></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>Frais de livraison (' . htmlspecialchars($user_commune) . '):</strong></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><strong>' . number_format($delivery_price, 0, ',', ' ') . ' FC</strong></td>
                    </tr>
                    <tr class="total-row" style="font-size: 16px; background-color: #e8747e; color: white;">
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>TOTAL À PAYER:</strong></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><strong>' . number_format($grand_total, 0, ',', ' ') . ' FC</strong></td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <p>© 2026 Merlita_Hair. Tous droits réservés.</p>
                <p>support@merlitahair.com</p>
            </div>
        </body>
        </html>' 
        ;
        
        $mail->AltBody = 'Bonjour, voici votre facture pour votre commande sur Merlita_Hair. Montant total: ' . number_format($grand_total, 0, ',', ' ') . ' FC. Merci de votre confiance.';
        $mail->send();
        
        // Afficher le message de succès après l'envoi
        echo '
        <div style="
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: 4px solid #155724;
            border-radius: 12px;
            padding: 40px 30px;
            margin: 30px auto;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        ">
            <div style="font-size: 48px; margin-bottom: 15px;">✓</div>
            <h2 style="
                color: white;
                font-size: 28px;
                margin: 0 0 15px 0;
                font-weight: bold;
            ">Commande effectuée!</h2>
            <p style="
                color: white;
                font-size: 16px;
                margin: 10px 0;
                line-height: 1.6;
            ">Votre facture a été envoyée avec succès à <strong>' . htmlspecialchars($user_email) . '</strong></p>
            <p style="
                color: white;
                font-size: 14px;
                margin: 10px 0;
                font-style: italic;
            ">Montant total: <strong>' . number_format($grand_total, 0, ',', ' ') . ' FC</strong></p>
            <p style="
                color: white;
                font-size: 14px;
                margin: 10px 0;
                font-style: italic;
            ">Veuillez vérifier votre boîte de réception.</p>
            <a href="products.php" style="color: white; text-decoration: underline;"> Retour à la boutique </a>
        </div>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}








