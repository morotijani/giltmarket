<?php 
    require_once ("db_connection/conn.php");

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="color-scheme" content="dark light">
    <title>J.Spence – Web3 and Finance Dashboard</title>
    <link rel="stylesheet" type="text/css" href="<?= PROOT; ?>dist/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?= PROOT; ?>dist/css/utility.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f=satoshi@900,700,500,300,401,400&display=swap">
    <script defer="defer" data-domain="satoshi.webpixels.io" src="https://plausible.io/js/script.outbound-links.js"></script>
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-items-center vh-100 overflow-y-auto gradient-bottom-right start-indigo middle-yellow end-purple">
        <div class="container-xxl">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6 text-center h-100 order-lg-2">
                    <img src="<?= PROOT; ?>dist/media/ill-1.svg" alt="..."></div>
                    <div class="col-lg-4 order-lg-1">
                        <div class="">
                            <h1 class="display-1 mb-4">Ooops!</h1>
                            <p class="lead lh-relaxed">The page you are looking for could not be found.</p>
                            <div class="my-10">
                                <a href="index" class="btn btn-dark">Return Home</a></div>
                                <div class="vstack gap-5 w-lg-88">
                                    <div class="">
                                        <a href="documentation" class="h5 text-heading text-primary-hover">Documentation<i class="far fa-arrow-right ms-2"></i></a><p class="text-sm">Everything you need to know is here.</p>
                                    </div>
                                    <div class="">
                                        <a href="" class="h5 text-heading text-primary-hover">Need any support/help<i class="far fa-arrow-right ms-2"></i></a>
                                        <p class="text-sm">Get in contact with J.Spence Developer.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
</html>