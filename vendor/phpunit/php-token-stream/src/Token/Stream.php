<?php
/*
    * This file is part of php-token-stream.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

/**
 * A stream of PHP tokens.
 */
class PHP_Token_Stream implements ArrayAccess, Countable, SeekableIterator
{

    /**
     * @var array
     */
    protected static $customTokens = [
        '(' => 'PHP_Token_OPEN_BRACKET',
        ')' => 'PHP_Token_CLOSE_BRACKET',
        '[' => 'PHP_Token_OPEN_SQUARE',
        ']' => 'PHP_Token_CLOSE_SQUARE',
        '{' => 'PHP_Token_OPEN_CURLY',
        '}' => 'PHP_Token_CLOSE_CURLY',
        ';' => 'PHP_Token_SEMICOLON',
        '.' => 'PHP_Token_DOT',
        ',' => 'PHP_Token_COMMA',
        '=' => 'PHP_Token_EQUAL',
        '<' => 'PHP_Token_LT',
        '>' => 'PHP_Token_GT',
        '+' => 'PHP_Token_PLUS',
        '-' => 'PHP_Token_MINUS',
        '*' => 'PHP_Token_MULT',
        '/' => 'PHP_Token_DIV',
        '?' => 'PHP_Token_QUESTION_MARK',
        '!' => 'PHP_Token_EXCLAMATION_MARK',
        ':' => 'PHP_Token_COLON',
        '"' => 'PHP_Token_DOUBLE_QUOTES',
        '@' => 'PHP_Token_AT',
        '&' => 'PHP_Token_AMPERSAND',
        '%' => 'PHP_Token_PERCENT',
        '|' => 'PHP_Token_PIPE',
        '$' => 'PHP_Token_DOLLAR',
        '^' => 'PHP_Token_CARET',
        '~' => 'PHP_Token_TILDE',
        '`' => 'PHP_Token_BACKTICK'
    ];

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var array
     */
    protected $tokens = [];

    /**
     * @var integer
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $linesOfCode = [
        'loc' => 0,
        'cloc' => 0,
        'ncloc' => 0
    ];

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var array
     */
    protected $functions;

    /**
     * @var array
     */
    protected $includes;

    /**
     * @var array
     */
    protected $interfaces;

    /**
     * @var array
     */
    protected $traits;

    /**
     * @var array
     */
    protected $lineToFunctionMap = [];

    /**
     * Constructor.
     *
     * @param string $sourceCode
     */
    public function __construct($sourceCode)
    {
        if (is_file($sourceCode)) {
            $this->filename = $sourceCode;
            $sourceCode     = file_get_contents($sourceCode);
        }

        $this->scan($sourceCode);

    }//end __construct()

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->tokens = [];

    }//end __destruct()

    /**
     * @return string
     */
    public function __toString()
    {
        $buffer = '';

        foreach ($this as $token) {
            $buffer .= $token;
        }

        return $buffer;

    }//end __toString()

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;

    }//end getFilename()

    /**
     * Scans the source for sequences of characters and converts them into a
     * stream of tokens.
     *
     * @param string $sourceCode
     */
    protected function scan($sourceCode)
    {
        $id        = 0;
        $line      = 1;
        $tokens    = token_get_all($sourceCode);
        $numTokens = count($tokens);

        $lastNonWhitespaceTokenWasDoubleColon = false;

        for ($i = 0; $i < $numTokens; ++$i) {
            $token = $tokens[$i];
            $skip  = 0;

            if (is_array($token)) {
                $name = substr(token_name($token[0]), 2);
                $text = $token[1];

                if ($lastNonWhitespaceTokenWasDoubleColon && $name == 'CLASS') {
                    $name = 'CLASS_NAME_CONSTANT';
                } else if ($name == 'USE' && isset($tokens[($i + 2)][0]) && $tokens[($i + 2)][0] == T_FUNCTION) {
                    $name  = 'USE_FUNCTION';
                    $text .= $tokens[($i + 1)][1] . $tokens[($i + 2)][1];
                    $skip  = 2;
                }

                $tokenClass = 'PHP_Token_' . $name;
            } else {
                $text       = $token;
                $tokenClass = self::$customTokens[$token];
            }

            $this->tokens[] = new $tokenClass($text, $line, $this, $id++);
            $lines          = substr_count($text, "\n");
            $line          += $lines;

            if ($tokenClass == 'PHP_Token_HALT_COMPILER') {
                break;
            } else if ($tokenClass == 'PHP_Token_COMMENT'
                || $tokenClass == 'PHP_Token_DOC_COMMENT'
            ) {
                $this->linesOfCode['cloc'] += ($lines + 1);
            }

            if ($name == 'DOUBLE_COLON') {
                $lastNonWhitespaceTokenWasDoubleColon = true;
            } else if ($name != 'WHITESPACE') {
                $lastNonWhitespaceTokenWasDoubleColon = false;
            }

            $i += $skip;
        }//end for

        $this->linesOfCode['loc']   = substr_count($sourceCode, "\n");
        $this->linesOfCode['ncloc'] = ($this->linesOfCode['loc'] -
                                      $this->linesOfCode['cloc']);

    }//end scan()

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->tokens);

    }//end count()

    /**
     * @return PHP_Token[]
     */
    public function tokens()
    {
        return $this->tokens;

    }//end tokens()

    /**
     * @return array
     */
    public function getClasses()
    {
        if ($this->classes !== null) {
            return $this->classes;
        }

        $this->parse();

        return $this->classes;

    }//end getClasses()

    /**
     * @return array
     */
    public function getFunctions()
    {
        if ($this->functions !== null) {
            return $this->functions;
        }

        $this->parse();

        return $this->functions;

    }//end getFunctions()

    /**
     * @return array
     */
    public function getInterfaces()
    {
        if ($this->interfaces !== null) {
            return $this->interfaces;
        }

        $this->parse();

        return $this->interfaces;

    }//end getInterfaces()

    /**
     * @return array
     */
    public function getTraits()
    {
        if ($this->traits !== null) {
            return $this->traits;
        }

        $this->parse();

        return $this->traits;

    }//end getTraits()

    /**
     * Gets the names of all files that have been included
     * using include(), include_once(), require() or require_once().
     *
     * Parameter $categorize set to TRUE causing this function to return a
     * multi-dimensional array with categories in the keys of the first dimension
     * and constants and their values in the second dimension.
     *
     * Parameter $category allow to filter following specific inclusion type
     *
     * @param boolean $categorize OPTIONAL
     * @param string  $category   OPTIONAL Either 'require_once', 'require',
     *                            'include_once', 'include'.
     *
     * @return array
     */
    public function getIncludes($categorize=false, $category=null)
    {
        if ($this->includes === null) {
            $this->includes = [
                'require_once' => [],
                'require'      => [],
                'include_once' => [],
                'include'      => []
            ];

            foreach ($this->tokens as $token) {
                switch (get_class($token)) {
                    case 'PHP_Token_REQUIRE_ONCE':
                    case 'PHP_Token_REQUIRE':
                    case 'PHP_Token_INCLUDE_ONCE':
                    case 'PHP_Token_INCLUDE':
                        $this->includes[$token->getType()][] = $token->getName();
                    break;
                }
            }
        }

        if (isset($this->includes[$category])) {
            $includes = $this->includes[$category];
        } else if ($categorize === false) {
            $includes = array_merge(
                $this->includes['require_once'],
                $this->includes['require'],
                $this->includes['include_once'],
                $this->includes['include']
            );
        } else {
            $includes = $this->includes;
        }

        return $includes;

    }//end getIncludes()

    /**
     * Returns the name of the function or method a line belongs to.
     *
     * @return string or null if the line is not in a function or method
     */
    public function getFunctionForLine($line)
    {
        $this->parse();

        if (isset($this->lineToFunctionMap[$line])) {
            return $this->lineToFunctionMap[$line];
        }

    }//end getFunctionForLine()

    protected function parse()
    {
        $this->interfaces = [];
        $this->classes    = [];
        $this->traits     = [];
        $this->functions  = [];
        $class            = [];
        $classEndLine     = [];
        $trait            = false;
        $traitEndLine     = false;
        $interface        = false;
        $interfaceEndLine = false;

        foreach ($this->tokens as $token) {
            switch (get_class($token)) {
                case 'PHP_Token_HALT_COMPILER':
                return;

                case 'PHP_Token_INTERFACE':
                    $interface        = $token->getName();
                    $interfaceEndLine = $token->getEndLine();

                    $this->interfaces[$interface] = [
                        'methods'   => [],
                        'parent'    => $token->getParent(),
                        'keywords'  => $token->getKeywords(),
                        'docblock'  => $token->getDocblock(),
                        'startLine' => $token->getLine(),
                        'endLine'   => $interfaceEndLine,
                        'package'   => $token->getPackage(),
                        'file'      => $this->filename
                    ];
                break;

                case 'PHP_Token_CLASS':
                case 'PHP_Token_TRAIT':
                    $tmp = [
                        'methods'   => [],
                        'parent'    => $token->getParent(),
                        'interfaces' => $token->getInterfaces(),
                        'keywords'  => $token->getKeywords(),
                        'docblock'  => $token->getDocblock(),
                        'startLine' => $token->getLine(),
                        'endLine'   => $token->getEndLine(),
                        'package'   => $token->getPackage(),
                        'file'      => $this->filename
                    ];

                    if ($token instanceof PHP_Token_CLASS) {
                        $class[]        = $token->getName();
                        $classEndLine[] = $token->getEndLine();

                        $this->classes[$class[(count($class) - 1)]] = $tmp;
                    } else {
                        $trait                = $token->getName();
                        $traitEndLine         = $token->getEndLine();
                        $this->traits[$trait] = $tmp;
                    }
                break;

                case 'PHP_Token_FUNCTION':
                    $name = $token->getName();
                    $tmp  = [
                        'docblock'  => $token->getDocblock(),
                        'keywords'  => $token->getKeywords(),
                        'visibility' => $token->getVisibility(),
                        'signature' => $token->getSignature(),
                        'startLine' => $token->getLine(),
                        'endLine'   => $token->getEndLine(),
                        'ccn'       => $token->getCCN(),
                        'file'      => $this->filename
                    ];

                    if (empty($class)
                        && $trait === false
                        && $interface === false
                    ) {
                        $this->functions[$name] = $tmp;

                        $this->addFunctionToMap(
                            $name,
                            $tmp['startLine'],
                            $tmp['endLine']
                        );
                    } else if (!empty($class)) {
                        $this->classes[$class[(count($class) - 1)]]['methods'][$name] = $tmp;

                        $this->addFunctionToMap(
                            $class[(count($class) - 1)] . '::' . $name,
                            $tmp['startLine'],
                            $tmp['endLine']
                        );
                    } else if ($trait !== false) {
                        $this->traits[$trait]['methods'][$name] = $tmp;

                        $this->addFunctionToMap(
                            $trait . '::' . $name,
                            $tmp['startLine'],
                            $tmp['endLine']
                        );
                    } else {
                        $this->interfaces[$interface]['methods'][$name] = $tmp;
                    }//end if
                break;

                case 'PHP_Token_CLOSE_CURLY':
                    if (!empty($classEndLine)
                        && $classEndLine[(count($classEndLine) - 1)] == $token->getLine()
                    ) {
                        array_pop($classEndLine);
                        array_pop($class);
                    } else if ($traitEndLine !== false
                        && $traitEndLine == $token->getLine()
                    ) {
                        $trait        = false;
                        $traitEndLine = false;
                    } else if ($interfaceEndLine !== false
                        && $interfaceEndLine == $token->getLine()
                    ) {
                        $interface        = false;
                        $interfaceEndLine = false;
                    }
                break;
            }//end switch
        }//end foreach

    }//end parse()

    /**
     * @return array
     */
    public function getLinesOfCode()
    {
        return $this->linesOfCode;

    }//end getLinesOfCode()

    /**
     */
    public function rewind()
    {
        $this->position = 0;

    }//end rewind()

    /**
     * @return boolean
     */
    public function valid()
    {
        return isset($this->tokens[$this->position]);

    }//end valid()

    /**
     * @return integer
     */
    public function key()
    {
        return $this->position;

    }//end key()

    /**
     * @return PHP_Token
     */
    public function current()
    {
        return $this->tokens[$this->position];

    }//end current()

    /**
     */
    public function next()
    {
        $this->position++;

    }//end next()

    /**
     * @param integer $offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->tokens[$offset]);

    }//end offsetExists()

    /**
     * @param integer $offset
     *
     * @return mixed
     *
     * @throws OutOfBoundsException
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException(
                sprintf(
                    'No token at position "%s"',
                    $offset
                )
            );
        }

        return $this->tokens[$offset];

    }//end offsetGet()

    /**
     * @param integer $offset
     * @param mixed   $value
     */
    public function offsetSet($offset, $value)
    {
        $this->tokens[$offset] = $value;

    }//end offsetSet()

    /**
     * @param integer $offset
     *
     * @throws OutOfBoundsException
     */
    public function offsetUnset($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException(
                sprintf(
                    'No token at position "%s"',
                    $offset
                )
            );
        }

        unset($this->tokens[$offset]);

    }//end offsetUnset()

    /**
     * Seek to an absolute position.
     *
     * @param integer $position
     *
     * @throws OutOfBoundsException
     */
    public function seek($position)
    {
        $this->position = $position;

        if (!$this->valid()) {
            throw new OutOfBoundsException(
                sprintf(
                    'No token at position "%s"',
                    $this->position
                )
            );
        }

    }//end seek()

    /**
     * @param string  $name
     * @param integer $startLine
     * @param integer $endLine
     */
    private function addFunctionToMap($name, $startLine, $endLine)
    {
        for ($line = $startLine; $line <= $endLine; $line++) {
            $this->lineToFunctionMap[$line] = $name;
        }

    }//end addFunctionToMap()

}//end class
