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

final class Project extends Node
{

    public function __construct(string $directory)
    {
        $this->init();
        $this->setProjectSourceDirectory($directory);

    }//end __construct()

    public function getProjectSourceDirectory(): string
    {
        return $this->getContextNode()->getAttribute('source');

    }//end getProjectSourceDirectory()

    public function getBuildInformation(): BuildInformation
    {
        $buildNode = $this->getDom()->getElementsByTagNameNS(
            'https://schema.phpunit.de/coverage/1.0',
            'build'
        )->item(0);

        if (!$buildNode) {
            $buildNode = $this->getDom()->documentElement->appendChild(
                $this->getDom()->createElementNS(
                    'https://schema.phpunit.de/coverage/1.0',
                    'build'
                )
            );
        }

        return new BuildInformation($buildNode);

    }//end getBuildInformation()

    public function getTests(): Tests
    {
        $testsNode = $this->getContextNode()->getElementsByTagNameNS(
            'https://schema.phpunit.de/coverage/1.0',
            'tests'
        )->item(0);

        if (!$testsNode) {
            $testsNode = $this->getContextNode()->appendChild(
                $this->getDom()->createElementNS(
                    'https://schema.phpunit.de/coverage/1.0',
                    'tests'
                )
            );
        }

        return new Tests($testsNode);

    }//end getTests()

    public function asDom(): \DOMDocument
    {
        return $this->getDom();

    }//end asDom()

    private function init(): void
    {
        $dom = new \DOMDocument;
        $dom->loadXML('<?xml version="1.0" ?><phpunit xmlns="https://schema.phpunit.de/coverage/1.0"><build/><project/></phpunit>');

        $this->setContextNode(
            $dom->getElementsByTagNameNS(
                'https://schema.phpunit.de/coverage/1.0',
                'project'
            )->item(0)
        );

    }//end init()

    private function setProjectSourceDirectory(string $name): void
    {
        $this->getContextNode()->setAttribute('source', $name);

    }//end setProjectSourceDirectory()

}//end class
