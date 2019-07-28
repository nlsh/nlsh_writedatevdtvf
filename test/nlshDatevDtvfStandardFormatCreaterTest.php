<?php
/**
 * PHPUnitTest
 *
 * PHPUnitTest der Klasse nlshDatevDtvfStandardFormatCreater
 *
 * @package   nlsh_DatevDtvfStandardFormatCreator
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2018
 * @link      https://github.com/nlsh/nlsh_DatevDtvfStandardFormatCreator
 * @license   LGPL
 */

use datevDtvfStandardFormatCreator\classes\nlshDatevDtvfStandardFormatCreater;

/**
 * Test- Klasse
 */
class nlshDatevDtvfStandardFormatCreaterTest extends PHPUnit\Framework\TestCase
{


    // Array mit Testvariablen der ersten Zeile, ohne Fehler.
    const ARR_VALUE_FIRST_LINE_NO_ERROR = array(
        'Bezeichnung'      => 'PhpUnitTest',
        'Diktatkürzel'     => 'NLSH',
        'DataAlphaNumeric' => 'Alpha',
    );

    // Array mit Testvariablen der ersten Zeile, mit Fehler.
    const ARR_VALUE_FIRST_LINE_WITH_ERROR = array(
        'NoKey'       => 'EsGibtKeinKey',
        'IchHatteMal' => 'eineKatze',
    );

    // Array mit Testvariablen einer Buchungszeile, ohne Fehler.
    const ARR_VALUE_DATA_ROW_NO_ERROR = array(
         // Umsatz.
        1 => '22,50',
         // Soll/Haben-Kennzeichen.
        2 => 'S',
         // Kontonummer.
        7 => 4970,
         // Buchungstext.
        14 => 'Buchungstext'
    );

    // Array mit Testvariablen einer Buchungszeile, mit einem Fehler.
    const ARR_VALUE_DATA_ROW_WITH_ERROR = array(
         // Umsatz.
        9999999 => '22,50',
         // Soll/Haben-Kennzeichen.
        2 => 'S',
         // Kontonummer.
        7 => 4970,
         // Buchungstext.
        14 => 'Buchungstext'
    );

    // Array mit Testvariablen dreier Buchungszeilen, ohne Fehler.
    const ARR_VALUE_DATA_ARRAY_NO_ERROR = array(
        array(
             // Umsatz.
            1 => '20.50',
             // Soll/Haben-Kennzeichen.
            2 => 'S',
             // Kontonummer.
            7 => 4970,
             // Buchungstext.
            14 => 'Buchungstext'
        ),
        array(
             // Umsatz.
            1 => '21,50',
             // EU-Land u. UStID.
            42 => 'DE9856985745',
             // Kontonummer.
            7 => 4970,
             // Buchungstext.
            14 => 'Buchungstext'
        ),
        array(
             // Umsatz.
            1 => 22.50,
             // Soll/Haben-Kennzeichen.
            2 => 'H',
             // Kontonummer.
            7 => 4970,
             // Buchungstext.
            14 => 'Buchungstext'
        ),
    );
    // Array mit Testvariablen dreier Buchungszeilen, mit und ohne Fehler.
    const ARR_VALUE_DATA_ARRAY_MIXED_WITH_ERROR = array(
        array(
             // Fehler 1.
            99999  => '20,50',
             // Soll/Haben-Kennzeichen.
            2      => 'S',
             // Kontonummer.
            7      => 4970,
             // Buchungstext.
            14     => 'Buchungstext',
            // Fehler 2.
            123456 => '20,50',
        ),
        array(
             // Umsatz.
            1 => '21,50',
             // Fehler 3.
            500000 => 'DE9856985745',
             // Kontonummer.
            7 => 4970,
             // Buchungstext.
            14 => 'Buchungstext'
        ),
        array(
             // Umsatz.
            1 => '22,50',
             // Soll/Haben-Kennzeichen.
            2 => 'H',
             // Fehler 4.
            80000 => 4970,
             // Buchungstext.
            14 => 'Buchungstext'
        ),
    );

    /**
     * Testobjekt durch Klasse nlshDatevDtvfStandardFormatCreater erzeugen
     *
     * Für jeden Test wird ein neues Objekt aus der Klasse erzeugt.
     * Vorbelegt 'V8_DatevFelder.xml'
     *
     * @return void
     */
    protected function setUp()
    {
        $this->object = new nlshDatevDtvfStandardFormatCreater(
            'src/Resources/config/V8_DatevFelder.xml'
        );

    }//end setUp()

    /**
     * Testobjekt wieder löschen!
     *
     * Nach jedem Durchlauf einer Test- Methode
     * wird das zu testende Objekt wieder gelöscht.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->object = false;

    }//end tearDown()

    /**
     * Test der Klasse bei Initialisierung
     */

    /**
     * Keine XML Datei
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Die zu öffnende Datei test/Resources/config/keinXml.xml
     *                           konnte nicht als xml- Datei eingelesen werden!
     *
     * @return void
     */
    public function testNoXmlFile()
    {
         // Neues Objekt mit Datei erstellen, die keine XML Datei ist.
        $this->object = new nlshDatevDtvfStandardFormatCreater('test/Resources/config/keinXml.xml');

    }//end testNoXmlFile()

    /**
     * Keine Datei gefunden
     *
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Die zu öffnende Datei michGibtEsNicht.xml konnte nicht gefunden werden!
     *
     * @return void
     */
    public function testNoFile()
    {
         // Neues Objekt ohne Datei.
        $this->object = new nlshDatevDtvfStandardFormatCreater('michGibtEsNicht.xml');

    }//end testNoFile()

    /**
     * Kontruktor -> _initializeStdDatevFields()
     *
     * @return void
     */
    public function testInitializeArrDefFromXmlFile()
    {
          // $arrDefFromXmlFile darf nicht mehr Leer sein.
        $this->assertNotEmpty($this->object->arrDefFromXmlFile);

         // $arrDefFromXmlFile muss ein Array sein.
        $this->assertInternalType(
            'array',
            $this->object->arrDefFromXmlFile
        );

         // Kurztest, ob der Key 'Field' in $this->object->arrDefFromXmlFile vorhanden ist.
        $this->assertArrayHasKey(
            'Field',
            $this->object->arrDefFromXmlFile
        );

    }//end testInitializeArrDefFromXmlFile()

    /**
     * Kontruktor -> _initializeSecondLine()
     *
     * @return void
     */
    public function testInitializeSecondLine()
    {
         // $strSecondLine darf nicht leer sein.
        $this->AssertNotEmpty($this->object->strSecondLine);

         // $strSecondLine muss ein String sein.
        $this->assertInternalType(
            'string',
            $this->object->strSecondLine
        );

    }//end testInitializeSecondLine()

    /**
     * Kontruktor -> _initializeDataRowTemplate()
     *
     * @return void
     */
    public function testInitializeDataRowTemplate()
    {
         // $arrDataRowTemplate darf nicht leer sein.
        $this->AssertNotEmpty($this->object->arrDataRowTemplate);

         // $arrDataRowTemplate muss ein Array sein.
        $this->assertInternalType(
            'array',
            $this->object->arrDataRowTemplate
        );

    }//end testInitializeDataRowTemplate()

    /**
     * Die Klasse Testen
     *
     /*

    /**
     * Funktionen der Klasse testen
     */

    /**
     * Funktion editFirstLine()
     *
     * @return void
     */
    public function testEditFirstLine()
    {
         // Zuerst ohne Fehler.
        $this->assertTrue($this->object->editFirstLine('Bezeichnung', 'PhpunitTest'));

         // Jetzt mit falschen Key.
        $this->assertSame(
            'Der Key noKey existiert nicht in der ersten Zeile!',
            $this->object->editFirstLine('noKey', 'noKey')
        );

    }//end testEditFirstLine()

    /**
     * Funktion editFirstLineWithArray()
     *
     * @return void
     */
    public function testEditFirstLineWithArray()
    {
         // Zuerst ohne Fehler.
        $this->assertTrue(
            $this->object->editFirstLineWithArray(self::ARR_VALUE_FIRST_LINE_NO_ERROR)
        );

         // Jetzt mit einem Fehler.
        $this->assertSame(
            array(
                0 => array(
                    0 => array(
                        0 => 'NoKey',
                        1 => 'EsGibtKeinKey',
                    ),
                    1 => 'Der Key NoKey existiert nicht in der ersten Zeile!',
                ),
                1 => array(
                    0 => array(
                        0 => 'IchHatteMal',
                        1 => 'eineKatze',
                    ),
                    1 => 'Der Key IchHatteMal existiert nicht in der ersten Zeile!',
                ),
            ),
            $this->object->editFirstLineWithArray(
                self::ARR_VALUE_FIRST_LINE_WITH_ERROR
            )
        );

         // Felder ersetzt?
        foreach (self::ARR_VALUE_FIRST_LINE_NO_ERROR as $key => $value) {
            $this->assertSame(
                $this->object->arrFirstLine[$key],
                $value
            );
        }

    }//end testEditFirstLineWithArray()

    /**
     * Funktion insertDataLine()
     *
     * @return void
     */
    public function testInsertDataLine()
    {
         // Zuerst mit Fehler.
         // Key existiert nicht, Rückgabe Fehlermeldung.
        $this->assertSame(
            $this->object->insertDataLine(array(99999 => 'MichGibtEsNicht')),
            array( 0 => 'FieldId 99999 existiert nicht!')
        );
         // $this-arrData muss dann natürlich Leer sein.
        $this->assertEmpty($this->object->arrData);

         // Jetzt ohne Fehler.
        $this->assertTrue($this->object->insertDataLine(self::ARR_VALUE_DATA_ROW_NO_ERROR));
         // $this-arrData darf nicht Leer sein
        $this->assertNotEmpty($this->object->arrData);

    }//end testInsertDataLine()

    /**
     * Funktion insertDataArray()
     *
     * @return void
     */
    public function testInsertDataArray()
    {
         // Zuerst mit Fehler
         // Fehlermeldungen zusammenbasteln, zuerst die Werte.
        foreach (self::ARR_VALUE_DATA_ARRAY_MIXED_WITH_ERROR as $value) {
            $error[][] = array($value);
        }

         // Jetzt um die Fehlermeldungen erweitern
         // Fehler 1. Buchungszeile mit 2 Fehlern.
        $error[0][1][0] = 'FieldId 99999 existiert nicht!';
        $error[0][1][1] = 'FieldId 123456 existiert nicht!';
         // Fehler 2. Buchungszeile.
        $error[1][1][0] = 'FieldId 500000 existiert nicht!';
         // Fehler 3. Buchungszeile.
        $error[2][1][0] = 'FieldId 80000 existiert nicht!';
         // Dann testen.
        $this->assertSame(
            $this->object->insertDataArray(self::ARR_VALUE_DATA_ARRAY_MIXED_WITH_ERROR),
            $error
        );
         // $this-arrData muss dann natürlich Leer sein.
        $this->assertEmpty($this->object->arrData);

         // Jetzt ohne Fehler.
        $this->assertTrue($this->object->insertDataArray(self::ARR_VALUE_DATA_ARRAY_NO_ERROR));

         // $this-arrData darf nicht Leer sein
        $this->assertNotEmpty($this->object->arrData);

    }//end testInsertDataArray()

    /**
     *  Funktion getOutData()
     *
     * @return void
     */
    public function testGetOutData()
    {
         // Mit Daten füllen.
        $this->object->insertDataArray(self::ARR_VALUE_DATA_ARRAY_NO_ERROR);

         // Zurück als String.
        $this->assertInternalType('string', $this->object->getOutData());
        print_r($this->object->getOutData());

    }//end testGetOutData()

}//end class
