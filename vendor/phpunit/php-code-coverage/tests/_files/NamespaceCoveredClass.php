<?php
namespace Foo;

class CoveredParentClass
{

    private function privateMethod()
    {

    }//end privateMethod()

    protected function protectedMethod()
    {
        $this->privateMethod();

    }//end protectedMethod()

    public function publicMethod()
    {
        $this->protectedMethod();

    }//end publicMethod()

}//end class

class CoveredClass extends CoveredParentClass
{

    private function privateMethod()
    {

    }//end privateMethod()

    protected function protectedMethod()
    {
        parent::protectedMethod();
        $this->privateMethod();

    }//end protectedMethod()

    public function publicMethod()
    {
        parent::publicMethod();
        $this->protectedMethod();

    }//end publicMethod()

}//end class
