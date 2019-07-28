<?php declare(strict_types = 1);
namespace TheSeer\Tokenizer;

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{

    /**
     * @var  Token
     */
    private $token;

    protected function setUp()
    {
        $this->token = new Token(1, 'test-dummy', 'blank');

    }//end setUp()

    public function testTokenCanBeCreated()
    {
        $this->assertInstanceOf(Token::class, $this->token);

    }//end testTokenCanBeCreated()

    public function testTokenLineCanBeRetrieved()
    {
        $this->assertEquals(1, $this->token->getLine());

    }//end testTokenLineCanBeRetrieved()

    public function testTokenNameCanBeRetrieved()
    {
        $this->assertEquals('test-dummy', $this->token->getName());

    }//end testTokenNameCanBeRetrieved()

    public function testTokenValueCanBeRetrieved()
    {
        $this->assertEquals('blank', $this->token->getValue());

    }//end testTokenValueCanBeRetrieved()

}//end class
