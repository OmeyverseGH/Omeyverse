<?php
$grpmode = 1; 
require_once '../../grplib-php/init.php';

// Redirect official users back to requested page if they somehow end up here
if(isset($_SESSION['official_user']) && $_SESSION['official_user'] >= 1) {
    header('Location: /', true, 302);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omeyverse</title>
    <link rel="stylesheet" href="../css/maintenance6.css" type="text/css">
</head>
<body>
    <div class="white-box">
        <div class="content">
            <h1 class="maintenance-text">Omeyverse is undergoing maintenance.. Please try again later</h1>
            <?php if($maintenance_mode): ?>
            <p class="maintenance-subtext">Only official users can access the site at this time.</p>
            <?php endif; ?>
        </div>
        <div class="buttons">
            <a href="https://omeyverse.com">
                <button type="button" class="button">Refresh</button>
            </a>
            <a href="https://omeyverse.com/act/login">
                <button type="button" class="button">Login Here</button>
            </a>
        </div>
    </div>
</body>
</html>
