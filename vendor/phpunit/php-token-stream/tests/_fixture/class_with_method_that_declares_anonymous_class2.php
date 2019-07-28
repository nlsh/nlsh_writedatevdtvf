<?php
class Test
{

    public function methodOne()
    {
        $foo = new class {

            public function method_in_anonymous_class()
            {
                return true;
            }//end method_in_anonymous_class()

        };

        return $foo->method_in_anonymous_class();

    }//end methodOne()

    public function methodTwo()
    {
        return false;

    }//end methodTwo()

}//end class
