<?php declare(strict_types = 1);
namespace TheSeer\Tokenizer;

class NamespaceUri
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->ensureValidUri($value);
        $this->value = $value;

    }//end __construct()

    public function asString(): string
    {
        return $this->value;

    }//end asString()

    private function ensureValidUri($value)
    {
        if (strpos($value, ':') === false) {
            throw new NamespaceUriException(
                sprintf("Namespace URI '%s' must contain at least one colon", $value)
            );
        }

    }//end ensureValidUri()

}//end class
