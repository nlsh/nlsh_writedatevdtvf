<?php
/*
    * This file is part of the php-code-coverage package.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

namespace SebastianBergmann\CodeCoverage\Report\Xml;

final class Report extends File
{

    public function __construct(string $name)
    {
        $dom = new \DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><phpunit xmlns="https://schema.phpunit.de/coverage/1.0"><file /></phpunit>');

        $contextNode = $dom->getElementsByTagNameNS(
            'https://schema.phpunit.de/coverage/1.0',
            'file'
        )->item(0);

        parent::__construct($contextNode);

        $this->setName($name);

    }//end __construct()

    public function asDom(): \DOMDocument
    {
        return $this->getDomDocument();

    }//end asDom()

    public function getFunctionObject($name): Method
    {
        $node = $this->getContextNode()->appendChild(
            $this->getDomDocument()->createElementNS(
                'https://schema.phpunit.de/coverage/1.0',
                'function'
            )
        );

        return new Method($node, $name);

    }//end getFunctionObject()

    public function getClassObject($name): Unit
    {
        return $this->getUnitObject('class', $name);

    }//end getClassObject()

    public function getTraitObject($name): Unit
    {
        return $this->getUnitObject('trait', $name);

    }//end getTraitObject()

    public function getSource(): Source
    {
        $source = $this->getContextNode()->getElementsByTagNameNS(
            'https://schema.phpunit.de/coverage/1.0',
            'source'
        )->item(0);

        if (!$source) {
            $source = $this->getContextNode()->appendChild(
                $this->getDomDocument()->createElementNS(
                    'https://schema.phpunit.de/coverage/1.0',
                    'source'
                )
            );
        }

        return new Source($source);

    }//end getSource()

    private function setName($name): void
    {
        $this->getContextNode()->setAttribute('name', \basename($name));
        $this->getContextNode()->setAttribute('path', \dirname($name));

    }//end setName()

    private function getUnitObject($tagName, $name): Unit
    {
        $node = $this->getContextNode()->appendChild(
            $this->getDomDocument()->createElementNS(
                'https://schema.phpunit.de/coverage/1.0',
                $tagName
            )
        );

        return new Unit($node, $name);

    }//end getUnitObject()

}//end class
