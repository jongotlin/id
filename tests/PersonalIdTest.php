<?php

namespace ledgr\id;

class PersonalIdTest extends \PHPUnit_Framework_TestCase
{
    public function invalidStructureProvider()
    {
        return array(
            array('123456'),
            array('123456-'),
            array('-1234'),
            array('123456-123'),
            array('123456-12345'),
            array('1234567-1234'),
            array('1234567-1234'),
            array('123456-1A34'),
            array('12A456-1234'),
            array('123456+'),
            array('+1234'),
            array('123456+123'),
            array('123456+12345'),
            array('1234567+1234'),
            array('1234567+1234'),
            array('123456+1A34'),
            array('12A456+1234'),
            array('120101-A234'),
            array('120101-12345'),
            array('120101+A234'),
            array('120101+12345'),
            array('')
        );
    }

    /**
     * @expectedException ledgr\id\Exception\InvalidStructureException
     * @dataProvider invalidStructureProvider
     */
    public function testInvalidStructure($nr)
    {
        new PersonalId($nr);
    }

    public function invalidCheckDigitProvider()
    {
        return array(
            array('820323-2770'),
            array('820323-2771'),
            array('820323-2772'),
            array('820323-2773'),
            array('820323-2774'),
            array('820323-2776'),
            array('820323-2777'),
            array('820323-2778'),
            array('820323-2779'),
            array('820323+2770'),
            array('820323+2771'),
            array('820323+2772'),
            array('820323+2773'),
            array('820323+2774'),
            array('820323+2776'),
            array('820323+2777'),
            array('820323+2778'),
            array('820323+2779'),
            array('123456-1234'),
            array('123456+1234'),
        );
    }

    /**
     * @expectedException ledgr\id\Exception\InvalidCheckDigitException
     * @dataProvider invalidCheckDigitProvider
     */
    public function testInvalidCheckDigit($nr)
    {
        new PersonalId($nr);
    }

    public function interchangeableFormulasProvider()
    {
        return array(
            array(new PersonalId('820323-2775'), new PersonalId('8203232775')),
            array(new PersonalId('19820323-2775'), new PersonalId('198203232775')),
            array(new PersonalId('19820323-2775'), new PersonalId('19820323+2775'))
        );
    }

    /**
     * @dataProvider interchangeableFormulasProvider
     */
    public function testInterchangeableFormulas($a, $b)
    {
        $this->assertEquals($a, $b);
    }

    public function testCentry()
    {
        $id = new PersonalId('820323-2775');
        $this->assertEquals('1982', $id->getDate()->format('Y'));

        $id = new PersonalId('820323+2775');
        $this->assertEquals('1882', $id->getDate()->format('Y'));

        $id = new PersonalId('450415-0220');
        $this->assertEquals('1945', $id->getDate()->format('Y'));
    }

    public function testDelimiter()
    {
        $id = new PersonalId('19820323+2775');
        $this->assertEquals('820323-2775', $id->getId());

        $id = new PersonalId('18820323-2775');
        $this->assertEquals('820323+2775', $id->getId());
    }

    public function testSex()
    {
        $id = new PersonalId('820323-2775');
        $this->assertEquals(Id::SEX_MALE, $id->getSex());
        $this->assertTrue($id->isMale());
        $this->assertFalse($id->isFemale());
        $this->assertFalse($id->isSexUndefined());

        $id = new PersonalId('770314-0348');
        $this->assertEquals(Id::SEX_FEMALE, $id->getSex());
        $this->assertFalse($id->isMale());
        $this->assertTrue($id->isFemale());
        $this->assertFalse($id->isSexUndefined());
    }

    public function testDOB()
    {
        $id = new PersonalId('820323-2775');
        $this->assertEquals('1982-03-23', $id->getDOB());

        $id = new PersonalId('19820323-2775');
        $this->assertEquals('1982-03-23', $id->getDOB());
    }

    public function testToString()
    {
        $id = new PersonalId('820323-2775');
        $this->assertEquals('820323-2775', (string)$id);
    }

    public function testGetLongId()
    {
        $id = new PersonalId('820323-2775');
        $this->assertEquals('19820323-2775', $id->getLongId());
    }
}
