<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname(dirname($vendorDir));

return array(
    'Twig\\' => array($vendorDir . '/twig/twig/src'),
    'Symfony\\Polyfill\\Mbstring\\' => array($vendorDir . '/symfony/polyfill-mbstring'),
    'Symfony\\Polyfill\\Ctype\\' => array($vendorDir . '/symfony/polyfill-ctype'),
    'Rmcc\\' => array($baseDir . '/app/classes', $baseDir . '/app/core', $baseDir . '/app/controllers', $baseDir . '/app/models', $baseDir . '/app/extensions'),
    'PHPMailer\\PHPMailer\\' => array($vendorDir . '/phpmailer/phpmailer/src'),
    'Nahid\\QArray\\' => array($vendorDir . '/nahid/qarray/src'),
    'Nahid\\JsonQ\\' => array($vendorDir . '/nahid/jsonq/src'),
    'Imagine\\' => array($vendorDir . '/imagine/imagine/src'),
    'DeepCopy\\' => array($vendorDir . '/myclabs/deep-copy/src/DeepCopy'),
);
