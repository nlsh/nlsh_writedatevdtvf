<?php
/**
 * CreateClass
 *
 * Hilfsdatei zur Erzeugung der Klasse zum Zwecke der Entwicklung
 *
 * @package   nlsh_DatevDtvfStandardFormatCreator
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2018
 * @link      https://github.com/nlsh/nlsh_DatevDtvfStandardFormatCreator
 * @license   LGPL
 */

namespace datevDtvfStandardFormatCreator;

use datevDtvfStandardFormatCreator\classes\nlshDatevDtvfStandardFormatCreater;

require_once 'classes/nlshDatevDtvfStandardFormatCreater.php';

$class = new nlshDatevDtvfStandardFormatCreater();

$class->editFirstLineWithArray(
    array(
        'Bezeichnung'  => 'PhpunitTest',
        'Diktatkürzel' => 'NLSH'
    )
);

$error = $class->insertDataArray(
    array(
        array(
            1 => 22.50,
            2 => 'S',
        ),
        array(
            1 => 20.00,
            2 => 'H'
        ),
            array(
                    1 => 18.00,
                    2 => 'H'
            ),
    )
);

$class->getOutData('namlyöxcy');
