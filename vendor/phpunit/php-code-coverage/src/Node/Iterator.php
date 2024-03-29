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

/**
 * Recursive iterator for node object graphs.
 */
final class Iterator implements \RecursiveIterator
{

    /**
     * @var integer
     */
    private $position;

    /**
     * @var AbstractNode[]
     */
    private $nodes;

    public function __construct(Directory $node)
    {
        $this->nodes = $node->getChildNodes();

    }//end __construct()

    /**
     * Rewinds the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;

    }//end rewind()

    /**
     * Checks if there is a current element after calls to rewind() or next().
     */
    public function valid(): bool
    {
        return $this->position < \count($this->nodes);

    }//end valid()

    /**
     * Returns the key of the current element.
     */
    public function key(): int
    {
        return $this->position;

    }//end key()

    /**
     * Returns the current element.
     */
    public function current(): AbstractNode
    {
        return $this->valid() ? $this->nodes[$this->position] : null;

    }//end current()

    /**
     * Moves forward to next element.
     */
    public function next(): void
    {
        $this->position++;

    }//end next()

    /**
     * Returns the sub iterator for the current element.
     *
     * @return Iterator
     */
    public function getChildren(): self
    {
        return new self($this->nodes[$this->position]);

    }//end getChildren()

    /**
     * Checks whether the current element has children.
     *
     * @return boolean
     */
    public function hasChildren(): bool
    {
        return $this->nodes[$this->position] instanceof Directory;

    }//end hasChildren()

}//end class
