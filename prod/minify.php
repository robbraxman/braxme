<?php
$path = '../libs';
require_once $path . '/minify-master/src/Minify.php';
require_once $path . '/minify-master/src/CSS.php';
require_once $path . '/minify-master/src/JS.php';
require_once $path . '/minify-master/src/Exception.php';
require_once $path . '/path-converter-master/src/Converter.php';

$minifier = new Minify\CSS('body { color: red; }');
exit();
echo $minifier->minify();
exit();
