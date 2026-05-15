<?php
session_start();

$studentNo = $_SESSION['student_no'] ?? '';

if ($studentNo === '') {
    header('Location: login.html?error=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Başarılı</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="login-page">
        <section class="login-card">
            <p class="login-message success">Hoşgeldiniz <?php echo htmlspecialchars($studentNo, ENT_QUOTES, 'UTF-8'); ?></p>
            <a class="back-link" href="../Hakkımda/Hakkımda.html">Siteye dön</a>
        </section>
    </main>
</body>
</html>
