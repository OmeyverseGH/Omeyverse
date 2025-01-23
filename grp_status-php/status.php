<?php
function checkWebsite($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_exec($ch);

    // Get the final redirected URL
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    // Get the HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Check for maintenance route
    $isMaintenance = strpos($finalUrl, '/error/maintenance.php') !== false;

    return [
        'httpCode' => $httpCode,
        'isMaintenance' => $isMaintenance,
    ];
}

$url = "https://omeyverse.com";
$checkResult = checkWebsite($url);

$statusCode = $checkResult['httpCode'];
$isMaintenance = $checkResult['isMaintenance'];
$isOnline = ($statusCode >= 200 && $statusCode < 400 && !$isMaintenance);
$lastChecked = date('Y-m-d H:i:s');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omeyverse Status</title>
    <link rel="stylesheet" href="omeyversestatus.css">
</head>
<body>
    <div class="container">
        <h1>Omeyverse Status</h1>
        <div class="status-card">
            <div class="status-indicator <?php echo $isMaintenance ? 'maintenance' : ($isOnline ? 'online' : 'offline'); ?>">
                <?php 
                if ($isMaintenance) {
                    echo 'Maintenance';
                } elseif ($isOnline) {
                    echo 'Online';
                } else {
                    echo 'Offline';
                }
                ?>
            </div>
            <p>
                <a href="<?php echo $url; ?>" class="url" target="_blank">
                    <?php echo $url; ?>
                </a>
            </p>
            <div class="details">
                Status Code: <?php echo $statusCode; ?><br>
                Last Checked: <?php echo $lastChecked; ?>
            </div>
        </div>
        <form method="post">
            <button type="submit" class="refresh-btn">Refresh Status</button>
        </form>
    </div>
</body>
</html>
