<?php
use PHPUnit\Framework\TestCase;

class CoverageNotPrivateTest extends TestCase
{

    /**
     * @covers CoveredClass::<!private>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();

    }//end testSomething()

}//end class
