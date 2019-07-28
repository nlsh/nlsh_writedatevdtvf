<?php
class BankAccount
{

    protected $balance = 0;

    public function getBalance()
    {
        return $this->balance;

    }//end getBalance()

    protected function setBalance($balance)
    {
        if ($balance >= 0) {
            $this->balance = $balance;
        } else {
            throw new RuntimeException;
        }

    }//end setBalance()

    public function depositMoney($balance)
    {
        $this->setBalance($this->getBalance() + $balance);

        return $this->getBalance();

    }//end depositMoney()

    public function withdrawMoney($balance)
    {
        $this->setBalance($this->getBalance() - $balance);

        return $this->getBalance();

    }//end withdrawMoney()

}//end class
