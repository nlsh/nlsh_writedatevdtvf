<?php
/*
    * This file is part of code-unit-reverse-lookup.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

namespace SebastianBergmann\CodeUnitReverseLookup;

/**
 * @since Class available since Release 1.0.0
 */
class Wizard
{

    /**
     * @var array
     */
    private $lookupTable = [];

    /**
     * @var array
     */
    private $processedClasses = [];

    /**
     * @var array
     */
    private $processedFunctions = [];

    /**
     * @param string  $filename
     * @param integer $lineNumber
     *
     * @return string
     */
    public function lookup($filename, $lineNumber)
    {
        if (!isset($this->lookupTable[$filename][$lineNumber])) {
            $this->updateLookupTable();
        }

        if (isset($this->lookupTable[$filename][$lineNumber])) {
            return $this->lookupTable[$filename][$lineNumber];
        } else {
            return $filename . ':' . $lineNumber;
        }

    }//end lookup()

    private function updateLookupTable()
    {
        $this->processClassesAndTraits();
        $this->processFunctions();

    }//end updateLookupTable()

    private function processClassesAndTraits()
    {
        foreach (array_merge(get_declared_classes(), get_declared_traits()) as $classOrTrait) {
            if (isset($this->processedClasses[$classOrTrait])) {
                continue;
            }

            $reflector = new \ReflectionClass($classOrTrait);

            foreach ($reflector->getMethods() as $method) {
                $this->processFunctionOrMethod($method);
            }

            $this->processedClasses[$classOrTrait] = true;
        }

    }//end processClassesAndTraits()

    private function processFunctions()
    {
        foreach (get_defined_functions()['user'] as $function) {
            if (isset($this->processedFunctions[$function])) {
                continue;
            }

            $this->processFunctionOrMethod(new \ReflectionFunction($function));

            $this->processedFunctions[$function] = true;
        }

    }//end processFunctions()

    /**
     * @param \ReflectionFunctionAbstract $functionOrMethod
     */
    private function processFunctionOrMethod(\ReflectionFunctionAbstract $functionOrMethod)
    {
        if ($functionOrMethod->isInternal()) {
            return;
        }

        $name = $functionOrMethod->getName();

        if ($functionOrMethod instanceof \ReflectionMethod) {
            $name = $functionOrMethod->getDeclaringClass()->getName() . '::' . $name;
        }

        if (!isset($this->lookupTable[$functionOrMethod->getFileName()])) {
            $this->lookupTable[$functionOrMethod->getFileName()] = [];
        }

        foreach (range($functionOrMethod->getStartLine(), $functionOrMethod->getEndLine()) as $line) {
            $this->lookupTable[$functionOrMethod->getFileName()][$line] = $name;
        }

    }//end processFunctionOrMethod()

}//end class
