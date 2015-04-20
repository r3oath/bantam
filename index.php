<?php

// Required to get Bantam up and running.
require('Autoload.php');

// Load in our class requirements for this page.
use App\Utils\Data\Assets;
use App\Utils\Misc\Config;
use App\Utils\User\I18N;

// Load our config file.
Config::create(Assets::load('app/config.php'));

// Load our internationalization configuration and set the locale.
I18N::folder('app/locale');
I18N::locale('en');

?>

<!DOCTYPE html>
<html lang="<?php echo I18N::getLocale(); ?>">
<head>
    <!-- Site information. -->
    <meta charset="UTF-8">
    <title><?php I18N::e('bantam.title'); ?></title>

    <!-- Load CSS assets. -->
    <?php Assets::css('//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css'); ?>
    <?php Assets::css('//fonts.googleapis.com/css?family=Amatic+SC|Open+Sans:300'); ?>
    <?php Assets::css('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'); ?>

    <!-- Page styles. -->
    <style>
        body {
            font-family: 'Amatic SC', cursive;
            font-weight: 400;
            font-size: 100%;
            background-color: #f5f5f5;
            color: #0f0f0f;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .container {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            position: absolute;
        }

        .content {
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform:translate(-50%, -50%);
            position: absolute;
            text-align: center;
        }

        .title {
            font-size: 6em;
            padding: 0px;
            margin: 0;
        }

        .quote {
            font-family: 'Open Sans', sans-serif;
            font-weight: 300;
            font-size: 1.2em;
            padding: 0px;
            margin: 0;
        }

        a {
            color: #a1a1a1;
            text-decoration: none;
            font-size: 2em;
            margin-top: 50px;
            display: inline-block;
            padding: 6px;
            padding-left: 12px;
            padding-right: 12px;
            border: 2px solid #f5f5f5;
        }

        a:hover {
            border: 2px dotted #a1a1a1;
            color: #313131;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <!-- Using internationalized strings. -->
            <p class="title"><?php I18N::e('bantam.welcome'); ?></p>

            <!-- Replacing a variable in an I18N string. -->
            <p class="quote"><?php I18N::e('bantam.quote', ['framework']); ?></p>

            <!-- The good ol' documentation. -->
            <p><a href="docs/index.html"><i class="fa fa-book"></i> <?php I18N::e('bantam.docs'); ?></a></p>
        </div>
    </div>
</body>
</html>
