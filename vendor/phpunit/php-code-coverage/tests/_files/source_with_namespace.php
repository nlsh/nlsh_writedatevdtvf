<?php
namespace bar\baz;

/**
 * Represents foo.
 */
class source_with_namespace
{
}//end class

/**
 * @param mixed $bar
 */
function &foo($bar)
{
    $baz = function () {
    };
    $a   = true ? true : false;
    $b   = "{$a}";
    $c   = "${b}";

}//end foo()
