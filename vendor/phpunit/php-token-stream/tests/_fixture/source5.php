<?php

function foo($a, array $b, array $c=array())
{

}//end foo()

interface i
{

    public function m($a, array $b, array $c=array());

}//end interface

abstract class a
{

    abstract public function m($a, array $b, array $c=array());

}//end class

class c
{

    public function m($a, array $b, array $c=array())
    {

    }//end m()

}//end class
