<?php
session_start();

$expectedStudentNo = 'b241210374';
$expectedEmail = $expectedStudentNo . '@sakarya.edu.tr';
$sakaryaMailPattern = '/^[a-z]\d{9,10}@sakarya\.edu\.tr$/';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($sakaryaMailPattern, $email)) {
    header('Location: login.html?error=1');
    exit;
}

if ($email === $expectedEmail && $password === $expectedStudentNo) {
    $_SESSION['student_no'] = $expectedStudentNo;
    header('Location: login_success.php');
    exit;
}

header('Location: login.html?error=1');
exit;
