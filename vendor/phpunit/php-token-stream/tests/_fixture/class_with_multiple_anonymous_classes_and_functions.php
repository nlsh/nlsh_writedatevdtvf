<?php
class class_with_multiple_anonymous_classes_and_functions
{

    public function m()
    {
        $c = new class {

            public function n()
            {
                return true;
            }//end n()

        };

        $d = new class {

            public function o()
            {
                return false;
            }//end o()

        };

        $f = function ($a, $b) {
            return ($a + $b);
        };

        $g = function ($a, $b) {
            return ($a - $b);
        };

    }//end m()

}//end class
