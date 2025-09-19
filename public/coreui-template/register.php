<?php
require_once 'includes/config.php';

$pageTitle = 'Register - ' . $config['siteName'];
$pageDescription = 'User registration page';
$currentPage = 'register';
$currentSection = '';

$additionalCSS = [];
$additionalJS = [];

$breadcrumbs = [
    ['title' => 'Register']
];

include 'includes/head.php';
?>

<?php include 'includes/sidebar.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
<?php include 'includes/header.php'; ?>
      
<?php include 'includes/footer.php'; ?>
    </div>

<?php include 'includes/scripts.php'; ?>