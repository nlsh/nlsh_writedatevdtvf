<?php declare(strict_types = 1);
namespace TheSeer\Tokenizer;

class TokenCollection implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @var integer
     */
    private $pos;

    /**
     * @param Token $token
     */
    public function addToken(Token $token)
    {
        $this->tokens[] = $token;

    }//end addToken()

    /**
     * @return Token
     */
    public function current(): Token
    {
        return current($this->tokens);

    }//end current()

    /**
     * @return integer
     */
    public function key(): int
    {
        return key($this->tokens);

    }//end key()

    /**
     * @return void
     */
    public function next()
    {
        next($this->tokens);
        $this->pos++;

    }//end next()

    /**
     * @return boolean
     */
    public function valid(): bool
    {
        return $this->count() > $this->pos;

    }//end valid()

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->tokens);
        $this->pos = 0;

    }//end rewind()

    /**
     * @return integer
     */
    public function count(): int
    {
        return count($this->tokens);

    }//end count()

    /**
     * @param mixed $offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->tokens[$offset]);

    }//end offsetExists()

    /**
     * @param mixed $offset
     *
     * @return Token
     * @throws TokenCollectionException
     */
    public function offsetGet($offset): Token
    {
        if (!$this->offsetExists($offset)) {
            throw new TokenCollectionException(
                sprintf('No Token at offest %s', $offset)
            );
        }

        return $this->tokens[$offset];

    }//end offsetGet()

    /**
     * @param mixed $offset
     * @param Token $value
     *
     * @throws TokenCollectionException
     */
    public function offsetSet($offset, $value)
    {
        if (!is_int($offset)) {
            $type = gettype($offset);
            throw new TokenCollectionException(
                sprintf(
                    'Offset must be of type integer, %s given',
                    $type === 'object' ? get_class($value) : $type
                )
            );
        }

        if (!$value instanceof Token) {
            $type = gettype($value);
            throw new TokenCollectionException(
                sprintf(
                    'Value must be of type %s, %s given',
                    Token::class,
                    $type === 'object' ? get_class($value) : $type
                )
            );
        }

        $this->tokens[$offset] = $value;

    }//end offsetSet()

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->tokens[$offset]);

    }//end offsetUnset()

}//end class
