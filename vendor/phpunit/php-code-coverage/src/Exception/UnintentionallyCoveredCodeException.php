<?php
/*
    * This file is part of the php-code-coverage package.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

namespace SebastianBergmann\CodeCoverage;

/**
 * Exception that is raised when code is unintentionally covered.
 */
final class UnintentionallyCoveredCodeException extends RuntimeException
{

    /**
     * @var array
     */
    private $unintentionallyCoveredUnits = [];

    /**
     * @param array $unintentionallyCoveredUnits
     */
    public function __construct(array $unintentionallyCoveredUnits)
    {
        $this->unintentionallyCoveredUnits = $unintentionallyCoveredUnits;

        parent::__construct($this->toString());

    }//end __construct()

    /**
     * @return array
     */
    public function getUnintentionallyCoveredUnits(): array
    {
        return $this->unintentionallyCoveredUnits;

    }//end getUnintentionallyCoveredUnits()

    /**
     * @return string
     */
    private function toString(): string
    {
        $message = '';

        foreach ($this->unintentionallyCoveredUnits as $unit) {
            $message .= '- ' . $unit . "\n";
        }

        return $message;

    }//end toString()

}//end class