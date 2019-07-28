<?php
/**
 * Represents foo.
 */
class Foo
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
