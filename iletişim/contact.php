<?php
function clean_text($value)
{
    return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
}

function clean_list($items)
{
    if (!is_array($items)) {
        return [];
    }

    return array_map('clean_text', $items);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact_form.html');
    exit;
}

$name = clean_text($_POST['name'] ?? '');
$email = clean_text($_POST['email'] ?? '');
$phone = clean_text($_POST['phone'] ?? '');
$websiteRaw = trim((string) ($_POST['website'] ?? ''));
$website = clean_text($websiteRaw);
$topic = clean_text($_POST['topic'] ?? '');
$category = clean_text($_POST['category'] ?? '');
$priority = clean_text($_POST['priority'] ?? '');
$contactMethod = clean_text($_POST['contact_method'] ?? '');
$date = clean_text($_POST['date'] ?? '');
$preferredTime = clean_text($_POST['preferred_time'] ?? '');
$urgency = clean_text($_POST['urgency'] ?? '');
$message = clean_text($_POST['message'] ?? '');
$consent = clean_text($_POST['consent'] ?? '');
$validationMethod = clean_text($_POST['validation_method'] ?? 'Doğrudan gönderim');
$attachmentInfo = 'Dosya seçilmedi';
$errors = [];

if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Ek dosya yüklenirken hata oluştu.';
        $attachmentInfo = 'Yükleme hatası';
    } else {
        $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg'];
        $attachmentName = clean_text($_FILES['attachment']['name'] ?? '');
        $attachmentExtension = strtolower(pathinfo($attachmentName, PATHINFO_EXTENSION));
        $attachmentSize = (int) ($_FILES['attachment']['size'] ?? 0);
        $attachmentType = clean_text($_FILES['attachment']['type'] ?? 'Tür bilinmiyor');

        if (!in_array($attachmentExtension, $allowedExtensions, true)) {
            $errors[] = 'Ek dosya yalnızca PDF, PNG veya JPG formatında olabilir.';
        }
        if ($attachmentSize > 2 * 1024 * 1024) {
            $errors[] = 'Ek dosya 2 MB boyutunu aşmamalıdır.';
        }

        $attachmentInfo = $attachmentName . ' - ' . number_format($attachmentSize / 1024, 1, ',', '.') . ' KB - ' . $attachmentType;
    }
}

if ($name === '') {
    $errors[] = 'İsim alanı boş bırakılamaz.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Geçerli bir e-mail adresi girilmelidir.';
}
if (!preg_match('/^\d{10,15}$/', $phone)) {
    $errors[] = 'Telefon alanına sadece 10-15 rakam girilmelidir.';
}
if ($websiteRaw !== '' && (!filter_var($websiteRaw, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//i', $websiteRaw))) {
    $errors[] = 'Web sitesi geçerli bir http/https URL formatında olmalıdır.';
}
if ($topic === '') {
    $errors[] = 'Konu alanı boş bırakılamaz.';
}
if ($category === '') {
    $errors[] = 'Konu türü seçilmelidir.';
}
if (!ctype_digit($priority) || (int) $priority < 1 || (int) $priority > 5) {
    $errors[] = 'Öncelik 1 ile 5 arasında bir sayı olmalıdır.';
}
if ($contactMethod === '') {
    $errors[] = 'Dönüş yolu seçilmelidir.';
}
if ($date === '') {
    $errors[] = 'Görüşme tarihi seçilmelidir.';
}
if (!preg_match('/^\d{2}:\d{2}$/', $preferredTime)) {
    $errors[] = 'Tercih edilen saat seçilmelidir.';
}
if (!ctype_digit($urgency) || (int) $urgency < 1 || (int) $urgency > 10) {
    $errors[] = 'Aciliyet seviyesi 1 ile 10 arasında olmalıdır.';
}
if ($message === '') {
    $errors[] = 'Mesaj alanı boş bırakılamaz.';
}
if ($consent === '') {
    $errors[] = 'Onay kutusu işaretlenmelidir.';
}

$submittedData = [
    'İsim' => $name,
    'Email' => $email,
    'Telefon' => $phone,
    'Web Sitesi' => $website !== '' ? $website : 'Belirtilmedi',
    'Konu' => $topic,
    'Konu Türü' => $category,
    'Öncelik' => $priority,
    'Dönüş Yolu' => $contactMethod,
    'Görüşme Tarihi' => $date,
    'Tercih Edilen Saat' => $preferredTime,
    'Aciliyet Seviyesi' => $urgency,
    'Ek Dosya' => $attachmentInfo,
    'Mesaj' => $message,
    'Onay' => $consent,
    'Kullanılan Denetim' => $validationMethod,
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim Formu Sonucu</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="result-page">
        <section class="result-card">
            <h1>İletişim Formu Sonucu</h1>

            <?php if (count($errors) > 0): ?>
                <div class="form-feedback error">
                    <strong>PHP denetimi hata buldu:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a class="result-link" href="contact_form.html">Forma geri dön</a>
            <?php else: ?>
                <p class="form-feedback success">PHP sayfası gelen tüm verileri başarıyla karşıladı.</p>
                <table class="result-table">
                    <tbody>
                        <?php foreach ($submittedData as $label => $value): ?>
                            <tr>
                                <th><?php echo $label; ?></th>
                                <td><?php echo nl2br($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a class="result-link" href="contact_form.html">Yeni form gönder</a>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
