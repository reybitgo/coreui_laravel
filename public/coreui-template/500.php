<?php
require_once 'includes/config.php';

$pageTitle = 'Error 500 - ' . $config['siteName'];
$pageDescription = '500 Internal Server Error page';
$currentPage = '500';
$currentSection = '';

$additionalCSS = [];
$additionalJS = [];

$breadcrumbs = [
    ['title' => 'Error 500']
];

include 'includes/head.php';
?>

<?php include 'includes/sidebar.php'; ?>
    <div class="wrapper d-flex flex-column min-vh-100">
<?php include 'includes/header.php'; ?>
      
<?php include 'includes/footer.php'; ?>
    </div>

<?php include 'includes/scripts.php'; ?>