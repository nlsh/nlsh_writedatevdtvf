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

use SebastianBergmann\CodeCoverage\InvalidArgumentException;

/**
 * Represents a directory in the code coverage information tree.
 */
final class Directory extends AbstractNode implements \IteratorAggregate
{

    /**
     * @var AbstractNode[]
     */
    private $children = [];

    /**
     * @var Directory[]
     */
    private $directories = [];

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * @var array
     */
    private $classes;

    /**
     * @var array
     */
    private $traits;

    /**
     * @var array
     */
    private $functions;

    /**
     * @var array
     */
    private $linesOfCode;

    /**
     * @var integer
     */
    private $numFiles = -1;

    /**
     * @var integer
     */
    private $numExecutableLines = -1;

    /**
     * @var integer
     */
    private $numExecutedLines = -1;

    /**
     * @var integer
     */
    private $numClasses = -1;

    /**
     * @var integer
     */
    private $numTestedClasses = -1;

    /**
     * @var integer
     */
    private $numTraits = -1;

    /**
     * @var integer
     */
    private $numTestedTraits = -1;

    /**
     * @var integer
     */
    private $numMethods = -1;

    /**
     * @var integer
     */
    private $numTestedMethods = -1;

    /**
     * @var integer
     */
    private $numFunctions = -1;

    /**
     * @var integer
     */
    private $numTestedFunctions = -1;

    /**
     * Returns the number of files in/under this node.
     */
    public function count(): int
    {
        if ($this->numFiles === -1) {
            $this->numFiles = 0;

            foreach ($this->children as $child) {
                $this->numFiles += \count($child);
            }
        }

        return $this->numFiles;

    }//end count()

    /**
     * Returns an iterator for this node.
     */
    public function getIterator(): \RecursiveIteratorIterator
    {
        return new \RecursiveIteratorIterator(
            new Iterator($this),
            \RecursiveIteratorIterator::SELF_FIRST
        );

    }//end getIterator()

    /**
     * Adds a new directory.
     */
    public function addDirectory(string $name): self
    {
        $directory = new self($name, $this);

        $this->children[]    = $directory;
        $this->directories[] = &$this->children[(\count($this->children) - 1)];

        return $directory;

    }//end addDirectory()

    /**
     * Adds a new file.
     *
     * @throws InvalidArgumentException
     */
    public function addFile(string $name, array $coverageData, array $testData, bool $cacheTokens): File
    {
        $file = new File($name, $this, $coverageData, $testData, $cacheTokens);

        $this->children[] = $file;
        $this->files[]    = &$this->children[(\count($this->children) - 1)];

        $this->numExecutableLines = -1;
        $this->numExecutedLines   = -1;

        return $file;

    }//end addFile()

    /**
     * Returns the directories in this directory.
     */
    public function getDirectories(): array
    {
        return $this->directories;

    }//end getDirectories()

    /**
     * Returns the files in this directory.
     */
    public function getFiles(): array
    {
        return $this->files;

    }//end getFiles()

    /**
     * Returns the child nodes of this node.
     */
    public function getChildNodes(): array
    {
        return $this->children;

    }//end getChildNodes()

    /**
     * Returns the classes of this node.
     */
    public function getClasses(): array
    {
        if ($this->classes === null) {
            $this->classes = [];

            foreach ($this->children as $child) {
                $this->classes = \array_merge(
                    $this->classes,
                    $child->getClasses()
                );
            }
        }

        return $this->classes;

    }//end getClasses()

    /**
     * Returns the traits of this node.
     */
    public function getTraits(): array
    {
        if ($this->traits === null) {
            $this->traits = [];

            foreach ($this->children as $child) {
                $this->traits = \array_merge(
                    $this->traits,
                    $child->getTraits()
                );
            }
        }

        return $this->traits;

    }//end getTraits()

    /**
     * Returns the functions of this node.
     */
    public function getFunctions(): array
    {
        if ($this->functions === null) {
            $this->functions = [];

            foreach ($this->children as $child) {
                $this->functions = \array_merge(
                    $this->functions,
                    $child->getFunctions()
                );
            }
        }

        return $this->functions;

    }//end getFunctions()

    /**
     * Returns the LOC/CLOC/NCLOC of this node.
     */
    public function getLinesOfCode(): array
    {
        if ($this->linesOfCode === null) {
            $this->linesOfCode = [
                'loc' => 0,
                'cloc' => 0,
                'ncloc' => 0
            ];

            foreach ($this->children as $child) {
                $linesOfCode = $child->getLinesOfCode();

                $this->linesOfCode['loc']   += $linesOfCode['loc'];
                $this->linesOfCode['cloc']  += $linesOfCode['cloc'];
                $this->linesOfCode['ncloc'] += $linesOfCode['ncloc'];
            }
        }

        return $this->linesOfCode;

    }//end getLinesOfCode()

    /**
     * Returns the number of executable lines.
     */
    public function getNumExecutableLines(): int
    {
        if ($this->numExecutableLines === -1) {
            $this->numExecutableLines = 0;

            foreach ($this->children as $child) {
                $this->numExecutableLines += $child->getNumExecutableLines();
            }
        }

        return $this->numExecutableLines;

    }//end getNumExecutableLines()

    /**
     * Returns the number of executed lines.
     */
    public function getNumExecutedLines(): int
    {
        if ($this->numExecutedLines === -1) {
            $this->numExecutedLines = 0;

            foreach ($this->children as $child) {
                $this->numExecutedLines += $child->getNumExecutedLines();
            }
        }

        return $this->numExecutedLines;

    }//end getNumExecutedLines()

    /**
     * Returns the number of classes.
     */
    public function getNumClasses(): int
    {
        if ($this->numClasses === -1) {
            $this->numClasses = 0;

            foreach ($this->children as $child) {
                $this->numClasses += $child->getNumClasses();
            }
        }

        return $this->numClasses;

    }//end getNumClasses()

    /**
     * Returns the number of tested classes.
     */
    public function getNumTestedClasses(): int
    {
        if ($this->numTestedClasses === -1) {
            $this->numTestedClasses = 0;

            foreach ($this->children as $child) {
                $this->numTestedClasses += $child->getNumTestedClasses();
            }
        }

        return $this->numTestedClasses;

    }//end getNumTestedClasses()

    /**
     * Returns the number of traits.
     */
    public function getNumTraits(): int
    {
        if ($this->numTraits === -1) {
            $this->numTraits = 0;

            foreach ($this->children as $child) {
                $this->numTraits += $child->getNumTraits();
            }
        }

        return $this->numTraits;

    }//end getNumTraits()

    /**
     * Returns the number of tested traits.
     */
    public function getNumTestedTraits(): int
    {
        if ($this->numTestedTraits === -1) {
            $this->numTestedTraits = 0;

            foreach ($this->children as $child) {
                $this->numTestedTraits += $child->getNumTestedTraits();
            }
        }

        return $this->numTestedTraits;

    }//end getNumTestedTraits()

    /**
     * Returns the number of methods.
     */
    public function getNumMethods(): int
    {
        if ($this->numMethods === -1) {
            $this->numMethods = 0;

            foreach ($this->children as $child) {
                $this->numMethods += $child->getNumMethods();
            }
        }

        return $this->numMethods;

    }//end getNumMethods()

    /**
     * Returns the number of tested methods.
     */
    public function getNumTestedMethods(): int
    {
        if ($this->numTestedMethods === -1) {
            $this->numTestedMethods = 0;

            foreach ($this->children as $child) {
                $this->numTestedMethods += $child->getNumTestedMethods();
            }
        }

        return $this->numTestedMethods;

    }//end getNumTestedMethods()

    /**
     * Returns the number of functions.
     */
    public function getNumFunctions(): int
    {
        if ($this->numFunctions === -1) {
            $this->numFunctions = 0;

            foreach ($this->children as $child) {
                $this->numFunctions += $child->getNumFunctions();
            }
        }

        return $this->numFunctions;

    }//end getNumFunctions()

    /**
     * Returns the number of tested functions.
     */
    public function getNumTestedFunctions(): int
    {
        if ($this->numTestedFunctions === -1) {
            $this->numTestedFunctions = 0;

            foreach ($this->children as $child) {
                $this->numTestedFunctions += $child->getNumTestedFunctions();
            }
        }

        return $this->numTestedFunctions;

    }//end getNumTestedFunctions()

}//end class
