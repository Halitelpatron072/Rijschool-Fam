<?php
$pdo = require "config.php"; ?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rijschool Familie Alkmaar</title>
    <link rel="stylesheet" href="css/style.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="topbar">
        <div class="topbar-container">

            <div class="socials">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-google"></i>
                <i class="fab fa-tiktok"></i>
            </div>

            <div class="contact-info">
                <span><i class="fa fa-envelope"></i> info@cobirijschool.nl</span>
            </div>
            <div class="phone-info">
                <a href="tel:+31684104098">
                    <i class="fa-solid fa-phone"></i>
                    06 84 10 40 98
                </a>
            </div>

        </div>
    </div>

    <header class="header">
        <div class="container">

            <div class="logo">
                <a href="index.php"><img src="img/rijschoolfoto-voorkant.png" alt="Rijschool Familie Alkmaar"></a>
            </div>

            <nav class="nav">
                <a href="#">Home</a>
                <a href="#">Over ons</a>
                <a href="#">Tarieven</a>
                <a href="#">Actie</a>
                <a href="#">Reviews</a>
                <a href="#">Contact</a>
            </nav>

            <a href="https://wa.me/31684104098" class="whatsapp">
                WhatsApp
            </a>

        </div>
    </header>

</body>

</html>
