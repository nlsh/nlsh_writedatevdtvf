<?php

/**
 * Docblock
 */
interface FooInterface
{

    public function bar();

}//end interface

class Foo
{

    public function bar()
    {

    }//end bar()

}//end class

function baz()
{
    // a one-line comment
    print '*';
    // a one-line comment
    // a one-line comment
    print '*';
    // a one-line comment
    /*
        a one-line comment
     */
    print '*'; /*
                   a one-line comment
    */

    print '*';
    // @codeCoverageIgnore
    print '*';
    // @codeCoverageIgnoreStart
    print '*';
    print '*';
    // @codeCoverageIgnoreEnd
    print '*';

}//end baz()
