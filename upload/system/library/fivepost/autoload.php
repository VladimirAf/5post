<?php
namespace Ipol\Fivepost;

spl_autoload_register(function ( $className ) {
    if( strpos($className, __NAMESPACE__) === 0) {
        $className = str_replace(__NAMESPACE__, '', $className);
        $classPath = implode(DIRECTORY_SEPARATOR, explode('\\', $className));
        $filename = __DIR__ . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . $classPath . ".php";

        if (is_readable($filename) && file_exists($filename))
        {
            require_once $filename;
        }
    }
});

