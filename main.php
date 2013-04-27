<?php
require_once 'bootstrap.php';

try {
$worker = new zymurgy\SiteInit\Worker();
$worker->writeSite();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
