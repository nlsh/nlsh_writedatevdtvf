<?php
// This file is example#1
// from http://www.php.net/manual/en/function.get-included-files.php
require 'test1.php';
require_once 'test2.php';
require 'test3.php';
require_once 'test4.php';

$included_files = get_included_files();

foreach ($included_files as $filename) {
    echo "$filename\n";
}
