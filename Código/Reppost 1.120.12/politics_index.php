<?php
session_start();

// Definir idioma por defecto si no está en la sesión
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'es';
}

// Manejar cambio de idioma
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_language'])) {
    $_SESSION['language'] = ($_SESSION['language'] == 'es') ? 'en' : 'es';
    header("Location: " . $_SERVER['PHP_SELF']); // Recargar la página
    exit;
}

$selectedLanguage = $_SESSION['language'];
?>

<!DOCTYPE html>
<html lang="<?php echo $selectedLanguage; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <title><?php echo ($selectedLanguage === 'es') ? 'Políticas de Uso - Reppost' : 'Usage Policies - Reppost'; ?></title>
    <link rel="stylesheet" href="View/css/politics.css">
    <link rel="stylesheet" href="View/css/dark_mode.css">
</head>

<body>
    <!-- Botón de cambio de idioma -->
    <form method="post" style="text-align: right; margin: 25px;">
        <button type="submit" name="change_language" class="language-btn">
            🌍 <?php echo ($selectedLanguage === 'es') ? 'English' : 'Español'; ?>
        </button>
    </form>
    <div class="policies-container">
        <h1><?php echo ($selectedLanguage === 'es') ? 'Políticas de Uso y Privacidad' : 'Usage and Privacy Policies'; ?></h1>
        <div class="policy-section">
            <h2><?php echo ($selectedLanguage === 'es') ? '1. Uso de Datos' : '1. Data Usage'; ?></h2>
            <p>
                <?php echo ($selectedLanguage === 'es')
                    ? 'Reppost utiliza tecnología blockchain para garantizar la seguridad e integridad de las interacciones. Todos los datos de publicaciones, interacciones y transacciones con tokens quedan registrados en la cadena de bloques y no pueden ser alterados.'
                    : 'Reppost uses blockchain technology to ensure the security and integrity of interactions. All data from posts, interactions, and token transactions are recorded on the blockchain and cannot be altered.'; ?>
            </p>
        </div>
        <div class="policy-section">
            <h2><?php echo ($selectedLanguage === 'es') ? '2. Privacidad y Seguridad' : '2. Privacy and Security'; ?></h2>
            <p>
                <?php echo ($selectedLanguage === 'es')
                    ? 'Tu información personal está protegida y encriptada. No compartimos tus datos con terceros sin tu consentimiento, excepto cuando sea requerido por ley.'
                    : 'Your personal information is protected and encrypted. We do not share your data with third parties without your consent, except when required by law.'; ?>
            </p>
        </div>
        <div class="policy-section">
            <h2><?php echo ($selectedLanguage === 'es') ? '3. Recompensas en Tokens' : '3. Token Rewards'; ?></h2>
            <p>
                <?php echo ($selectedLanguage === 'es')
                    ? 'Los usuarios reciben tokens por interacciones como publicaciones, comentarios y reacciones. Estos tokens pueden ser utilizados dentro de la plataforma o transferidos a billeteras externas compatibles con blockchain.'
                    : 'Users receive tokens for interactions such as posts, comments, and reactions. These tokens can be used within the platform or transferred to external wallets compatible with blockchain.'; ?>
            </p>
        </div>
        <div class="policy-section">
            <h2><?php echo ($selectedLanguage === 'es') ? '4. Prohibiciones y Sanciones' : '4. Prohibited Actions and Penalties'; ?></h2>
            <p>
                <?php echo ($selectedLanguage === 'es')
                    ? 'Está prohibido el uso de la plataforma para actividades fraudulentas, spam o abuso de tokens. Los usuarios que infrinjan estas normas pueden perder sus recompensas y ser expulsados de la plataforma.'
                    : 'The use of the platform for fraudulent activities, spam, or token abuse is prohibited. Users who violate these rules may lose their rewards and be expelled from the platform.'; ?>
            </p>
        </div>
        <div class="policy-section">
            <h2><?php echo ($selectedLanguage === 'es') ? '5. Contacto' : '5. Contact'; ?></h2>
            <p>
                <?php echo ($selectedLanguage === 'es')
                    ? 'Si tienes preguntas sobre nuestras políticas, puedes contactarnos en support@reppost.com.'
                    : 'If you have questions about our policies, you can contact us at support@reppost.com.'; ?>
            </p>
        </div>
        <a href="index.php" class="back-btn">
            <?php echo ($selectedLanguage === 'es') ? 'Volver' : 'Back to Home'; ?>
        </a>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
</footer>

</html>
