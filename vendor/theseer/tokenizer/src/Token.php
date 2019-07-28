<?php declare(strict_types = 1);
namespace TheSeer\Tokenizer;

class Token
{

    /**
     * @var integer
     */
    private $line;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * Token constructor.
     *
     * @param integer $line
     * @param string  $name
     * @param string  $value
     */
    public function __construct(int $line, string $name, string $value)
    {
        $this->line  = $line;
        $this->name  = $name;
        $this->value = $value;

    }//end __construct()

    /**
     * @return integer
     */
    public function getLine(): int
    {
        return $this->line;

    }//end getLine()

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;

    }//end getName()

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;

    }//end getValue()

}//end class
