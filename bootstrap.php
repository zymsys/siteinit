<?php

require_once 'SplClassLoader.php';
$classLoader = new SplClassLoader(null, __DIR__ . '/vendor');
$classLoader->register();
