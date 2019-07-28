<?php
use PHPUnit\Framework\TestCase;

class CoveragePublicTest extends TestCase
{

    /**
     * @covers CoveredClass::<public>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();

    }//end testSomething()

}//end class
