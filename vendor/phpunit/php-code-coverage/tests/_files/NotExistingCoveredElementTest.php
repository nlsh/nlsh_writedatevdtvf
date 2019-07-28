<?php
use PHPUnit\Framework\TestCase;

class NotExistingCoveredElementTest extends TestCase
{

    /**
     * @covers NotExistingClass
     */
    public function testOne()
    {

    }//end testOne()

    /**
     * @covers NotExistingClass::notExistingMethod
     */
    public function testTwo()
    {

    }//end testTwo()

    /**
     * @covers NotExistingClass::<public>
     */
    public function testThree()
    {

    }//end testThree()

}//end class
