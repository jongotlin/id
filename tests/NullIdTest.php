<?php

namespace byrokrat\id;

class NullIdTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSerialPreDelimiter()
    {
        $this->assertEquals(
            '000000',
            (new NullId)->getSerialPreDelimiter()
        );
    }

    public function testGetSerialPostDelimiter()
    {
        $this->assertEquals(
            '000',
            (new NullId)->getSerialPostDelimiter()
        );
    }

    public function testGetCheckDigit()
    {
        $this->assertEquals(
            '0',
            (new NullId)->getCheckDigit()
        );
    }

    public function testGetDelimiter()
    {
        $this->assertEquals(
            '-',
            (new NullId)->getDelimiter()
        );
    }

    public function testGetString()
    {
        NullId::setString('foobar');

        $this->assertEquals(
            'foobar',
            (string) new NullId
        );

        $this->assertEquals(
            'foobar',
            (new NullId)->getId()
        );
    }

    public function testGetDate()
    {
        $this->setExpectedException('byrokrat\id\Exception\DateNotSupportedException');
        (new NullId)->getDate();
    }

    public function testGetSex()
    {
        $nullId = new NullId();
        $this->assertEquals(Id::SEX_UNDEFINED, $nullId->getSex());
        $this->assertTrue($nullId->isSexUndefined());
        $this->assertFalse($nullId->isMale());
        $this->assertFalse($nullId->isFemale());
    }

    public function testGetLegalForm()
    {
        $nullId = new NullId();
        $this->assertEquals(Id::LEGAL_FORM_UNDEFINED, $nullId->getLegalForm());
        $this->assertTrue($nullId->isLegalFormUndefined());
        $this->assertFalse($nullId->isStateOrParish());
        $this->assertFalse($nullId->isIncorporated());
        $this->assertFalse($nullId->isPartnership());
        $this->assertFalse($nullId->isAssociation());
        $this->assertFalse($nullId->isNonProfit());
        $this->assertFalse($nullId->isTradingCompany());
    }

    public function testGetBirthCounty()
    {
        $this->assertEquals(
            Id::COUNTY_UNDEFINED,
            (new NullId)->getBirthCounty()
        );
    }
}
