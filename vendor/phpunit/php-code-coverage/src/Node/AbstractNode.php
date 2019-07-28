<?php
/*
    * This file is part of the php-code-coverage package.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

namespace SebastianBergmann\CodeCoverage\Node;

use SebastianBergmann\CodeCoverage\Util;

/**
 * Base class for nodes in the code coverage information tree.
 */
abstract class AbstractNode implements \Countable
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $pathArray;

    /**
     * @var AbstractNode
     */
    private $parent;

    /**
     * @var string
     */
    private $id;

    public function __construct(string $name, self $parent=null)
    {
        if (\substr($name, -1) == '/') {
            $name = \substr($name, 0, -1);
        }

        $this->name   = $name;
        $this->parent = $parent;

    }//end __construct()

    public function getName(): string
    {
        return $this->name;

    }//end getName()

    public function getId(): string
    {
        if ($this->id === null) {
            $parent = $this->getParent();

            if ($parent === null) {
                $this->id = 'index';
            } else {
                $parentId = $parent->getId();

                if ($parentId === 'index') {
                    $this->id = \str_replace(':', '_', $this->name);
                } else {
                    $this->id = $parentId . '/' . $this->name;
                }
            }
        }

        return $this->id;

    }//end getId()

    public function getPath(): string
    {
        if ($this->path === null) {
            if ($this->parent === null || $this->parent->getPath() === null || $this->parent->getPath() === false) {
                $this->path = $this->name;
            } else {
                $this->path = $this->parent->getPath() . '/' . $this->name;
            }
        }

        return $this->path;

    }//end getPath()

    public function getPathAsArray(): array
    {
        if ($this->pathArray === null) {
            if ($this->parent === null) {
                $this->pathArray = [];
            } else {
                $this->pathArray = $this->parent->getPathAsArray();
            }

            $this->pathArray[] = $this;
        }

        return $this->pathArray;

    }//end getPathAsArray()

    public function getParent(): ?self
    {
        return $this->parent;

    }//end getParent()

    /**
     * Returns the percentage of classes that has been tested.
     *
     * @return integer|string
     */
    public function getTestedClassesPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedClasses(),
            $this->getNumClasses(),
            $asString
        );

    }//end getTestedClassesPercent()

    /**
     * Returns the percentage of traits that has been tested.
     *
     * @return integer|string
     */
    public function getTestedTraitsPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedTraits(),
            $this->getNumTraits(),
            $asString
        );

    }//end getTestedTraitsPercent()

    /**
     * Returns the percentage of classes and traits that has been tested.
     *
     * @return integer|string
     */
    public function getTestedClassesAndTraitsPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedClassesAndTraits(),
            $this->getNumClassesAndTraits(),
            $asString
        );

    }//end getTestedClassesAndTraitsPercent()

    /**
     * Returns the percentage of functions that has been tested.
     *
     * @return integer|string
     */
    public function getTestedFunctionsPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedFunctions(),
            $this->getNumFunctions(),
            $asString
        );

    }//end getTestedFunctionsPercent()

    /**
     * Returns the percentage of methods that has been tested.
     *
     * @return integer|string
     */
    public function getTestedMethodsPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedMethods(),
            $this->getNumMethods(),
            $asString
        );

    }//end getTestedMethodsPercent()

    /**
     * Returns the percentage of functions and methods that has been tested.
     *
     * @return integer|string
     */
    public function getTestedFunctionsAndMethodsPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumTestedFunctionsAndMethods(),
            $this->getNumFunctionsAndMethods(),
            $asString
        );

    }//end getTestedFunctionsAndMethodsPercent()

    /**
     * Returns the percentage of executed lines.
     *
     * @return integer|string
     */
    public function getLineExecutedPercent(bool $asString=true)
    {
        return Util::percent(
            $this->getNumExecutedLines(),
            $this->getNumExecutableLines(),
            $asString
        );

    }//end getLineExecutedPercent()

    /**
     * Returns the number of classes and traits.
     */
    public function getNumClassesAndTraits(): int
    {
        return ($this->getNumClasses() + $this->getNumTraits());

    }//end getNumClassesAndTraits()

    /**
     * Returns the number of tested classes and traits.
     */
    public function getNumTestedClassesAndTraits(): int
    {
        return ($this->getNumTestedClasses() + $this->getNumTestedTraits());

    }//end getNumTestedClassesAndTraits()

    /**
     * Returns the classes and traits of this node.
     */
    public function getClassesAndTraits(): array
    {
        return \array_merge($this->getClasses(), $this->getTraits());

    }//end getClassesAndTraits()

    /**
     * Returns the number of functions and methods.
     */
    public function getNumFunctionsAndMethods(): int
    {
        return ($this->getNumFunctions() + $this->getNumMethods());

    }//end getNumFunctionsAndMethods()

    /**
     * Returns the number of tested functions and methods.
     */
    public function getNumTestedFunctionsAndMethods(): int
    {
        return ($this->getNumTestedFunctions() + $this->getNumTestedMethods());

    }//end getNumTestedFunctionsAndMethods()

    /**
     * Returns the functions and methods of this node.
     */
    public function getFunctionsAndMethods(): array
    {
        return \array_merge($this->getFunctions(), $this->getMethods());

    }//end getFunctionsAndMethods()

    /**
     * Returns the classes of this node.
     */
    abstract public function getClasses(): array;

    /**
     * Returns the traits of this node.
     */
    abstract public function getTraits(): array;

    /**
     * Returns the functions of this node.
     */
    abstract public function getFunctions(): array;

    /**
     * Returns the LOC/CLOC/NCLOC of this node.
     */
    abstract public function getLinesOfCode(): array;

    /**
     * Returns the number of executable lines.
     */
    abstract public function getNumExecutableLines(): int;

    /**
     * Returns the number of executed lines.
     */
    abstract public function getNumExecutedLines(): int;

    /**
     * Returns the number of classes.
     */
    abstract public function getNumClasses(): int;

    /**
     * Returns the number of tested classes.
     */
    abstract public function getNumTestedClasses(): int;

    /**
     * Returns the number of traits.
     */
    abstract public function getNumTraits(): int;

    /**
     * Returns the number of tested traits.
     */
    abstract public function getNumTestedTraits(): int;

    /**
     * Returns the number of methods.
     */
    abstract public function getNumMethods(): int;

    /**
     * Returns the number of tested methods.
     */
    abstract public function getNumTestedMethods(): int;

    /**
     * Returns the number of functions.
     */
    abstract public function getNumFunctions(): int;

    /**
     * Returns the number of tested functions.
     */
    abstract public function getNumTestedFunctions(): int;

}//end class
