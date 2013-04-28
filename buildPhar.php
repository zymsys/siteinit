<?php
$files = array(
    'bootstrap.php',
    'main.php',
    'SplClassLoader.php',
    'classes/zymurgy/PHPAPI/IExecution.php',
    'classes/zymurgy/PHPAPI/IFilesystem.php',
    'classes/zymurgy/PHPAPI/IMySQL.php',
    'classes/zymurgy/PHPAPI/IPOSIX.php',
    'classes/zymurgy/PHPAPI/IString.php',
    'classes/zymurgy/PHPAPI/MockFileHandle.php',
    'classes/zymurgy/PHPAPI/MockReturnSet.php',
    'classes/zymurgy/PHPAPI/Repository.php',
    'classes/zymurgy/PHPAPI/Production/Execution.php',
    'classes/zymurgy/PHPAPI/Production/Filesystem.php',
    'classes/zymurgy/PHPAPI/Production/MySQL.php',
    'classes/zymurgy/PHPAPI/Production/POSIX.php',
    'classes/zymurgy/PHPAPI/Production/String.php',
    'classes/zymurgy/SiteInit/Config.php',
    'classes/zymurgy/SiteInit/Configurator.php',
    'classes/zymurgy/SiteInit/SiteInit.php',
    'classes/zymurgy/SiteInit/Worker.php',
);
$archive = new Phar('siteinit.phar');
foreach ($files as $file) {
    $archive->addFile($file);
}
$stub = "#!/usr/bin/env php\n" . $archive->createDefaultStub('main.php');
$archive->setStub($stub);
