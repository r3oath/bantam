<?php

$src = 'src/';
$build = 'build/';

if(file_exists($build.'bantam.phar')) {
    unlink($build.'bantam.phar');
}

if(file_exists($build.'bantam.phar.gz')) {
    unlink($build.'bantam.phar.gz');
}

$phar = new Phar($build.'bantam.phar');
$phar->setDefaultStub('index.php', $src.'index.php');
$phar->buildFromDirectory($src);
$phar->compress(Phar::GZ);

echo 'Build finished!';
