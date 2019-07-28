<?php

/**
 * Some comment
 */
class Foo
{

    function foo()
    {

    }//end foo()

    /**
     * @param Baz $baz
     */
    public function bar(Baz $baz)
    {

    }//end bar()

    /**
     * @param Foobar $foobar
     */
    static public function foobar(Foobar $foobar)
    {

    }//end foobar()

    public function barfoo(Barfoo $barfoo)
    {

    }//end barfoo()

    /**
     * This docblock does not belong to the baz function
     */

    public function baz()
    {

    }//end baz()

    public function blaz($x, $y)
    {

    }//end blaz()

}//end class
