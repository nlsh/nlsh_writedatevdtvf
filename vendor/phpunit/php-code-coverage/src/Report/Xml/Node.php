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

abstract class Node
{

    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @var \DOMElement
     */
    private $contextNode;

    public function __construct(\DOMElement $context)
    {
        $this->setContextNode($context);

    }//end __construct()

    public function getDom(): \DOMDocument
    {
        return $this->dom;

    }//end getDom()

    public function getTotals(): Totals
    {
        $totalsContainer = $this->getContextNode()->firstChild;

        if (!$totalsContainer) {
            $totalsContainer = $this->getContextNode()->appendChild(
                $this->dom->createElementNS(
                    'https://schema.phpunit.de/coverage/1.0',
                    'totals'
                )
            );
        }

        return new Totals($totalsContainer);

    }//end getTotals()

    public function addDirectory(string $name): Directory
    {
        $dirNode = $this->getDom()->createElementNS(
            'https://schema.phpunit.de/coverage/1.0',
            'directory'
        );

        $dirNode->setAttribute('name', $name);
        $this->getContextNode()->appendChild($dirNode);

        return new Directory($dirNode);

    }//end addDirectory()

    public function addFile(string $name, string $href): File
    {
        $fileNode = $this->getDom()->createElementNS(
            'https://schema.phpunit.de/coverage/1.0',
            'file'
        );

        $fileNode->setAttribute('name', $name);
        $fileNode->setAttribute('href', $href);
        $this->getContextNode()->appendChild($fileNode);

        return new File($fileNode);

    }//end addFile()

    protected function setContextNode(\DOMElement $context): void
    {
        $this->dom         = $context->ownerDocument;
        $this->contextNode = $context;

    }//end setContextNode()

    protected function getContextNode(): \DOMElement
    {
        return $this->contextNode;

    }//end getContextNode()

}//end class
