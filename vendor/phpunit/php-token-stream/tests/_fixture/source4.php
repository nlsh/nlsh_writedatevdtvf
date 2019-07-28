<?php
// Declare the interface 'iTemplate'
interface iTemplate
{

    public function setVariable($name, $var);

    public function getHtml($template);

}//end interface

interface a
{

    public function foo();

}//end interface

interface b extends a
{

    public function baz(Baz $baz);

}//end interface

// short desc for class that implement a unique interface
class c implements b
{

    public function foo()
    {

    }//end foo()

    public function baz(Baz $baz)
    {

    }//end baz()

}//end class
