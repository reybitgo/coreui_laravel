<?php
require_once 'includes/config.php';

$pageTitle = 'Login - ' . $config['siteName'];
$pageDescription = 'User login page';
$currentPage = 'login';
$currentSection = '';

$additionalCSS = [];
$additionalJS = [];

$breadcrumbs = [
    ['title' => 'Login']
];

include 'includes/head.php';
?>

<?php include 'includes/sidebar.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
<?php include 'includes/header.php'; ?>
      
<?php include 'includes/footer.php'; ?>
    </div>

<?php include 'includes/scripts.php'; ?>