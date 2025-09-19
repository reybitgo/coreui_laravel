<?php
require_once 'includes/config.php';

$pageTitle = 'Error 404 - ' . $config['siteName'];
$pageDescription = '404 Page Not Found error page';
$currentPage = '404';
$currentSection = '';

$additionalCSS = [];
$additionalJS = [];

$breadcrumbs = [
    ['title' => 'Error 404']
];

include 'includes/head.php';
?>

<?php include 'includes/sidebar.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
<?php include 'includes/header.php'; ?>
      
<?php include 'includes/footer.php'; ?>
    </div>

<?php include 'includes/scripts.php'; ?>