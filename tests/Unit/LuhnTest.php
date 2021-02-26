<?php

namespace Tests\Unit;

use App\Helpers\LuhnChecker;
use PHPUnit\Framework\TestCase;

class LuhnTest extends TestCase
{

    /**
     * Cases where it should fail in the prevalidation state
     * @return void
     */
    public function testPreValidateFails()
    {
        $this->assertFalse(LuhnChecker::check('123456789'));
        $this->assertFalse(LuhnChecker::check('123456789'));
        $this->assertFalse(LuhnChecker::check('asd123asd123sdsa'));
        $this->assertFalse(LuhnChecker::check('000000000000'));
        $this->assertFalse(LuhnChecker::check('000'));
        $this->assertFalse(LuhnChecker::check('0000000000000000000'));
    }


    /**
     * Tests an AmericanExpress type of card
     *
     * @return void
     */
    public function testAmericanExpressCard()
    {
        $this->assertTrue(LuhnChecker::check('371449635398431')); // valid American Express Card
        $this->assertFalse(LuhnChecker::check('37144963539843'));
        $this->assertFalse(LuhnChecker::check('37144963539843123'));
    }

    /**
     * Tests a DinnersClub type of card
     */
    public function testDinnersClubCard()
    {
        $this->assertTrue(LuhnChecker::check('30569309025904')); // valid Dinners Club Card
        $this->assertFalse(LuhnChecker::check('3056930902590'));
        $this->assertFalse(LuhnChecker::check('305693090259042'));
    }

    /**
     * Tests a DinnersClub type of card
     */
    public function testDiscoverCard()
    {
        $this->assertTrue(LuhnChecker::check('6011111111111117')); // valid Discover Card
        $this->assertFalse(LuhnChecker::check('601111111111111'));
        $this->assertFalse(LuhnChecker::check('60111111111111171'));
    }

    /**
     * Tests a JCB type of card
     */
    public function testJcbCard()
    {
        $this->assertTrue(LuhnChecker::check('3530111333300000')); // valid JCB Card
        $this->assertFalse(LuhnChecker::check('353011133330002'));
        $this->assertFalse(LuhnChecker::check('35301113333000002'));
    }

    /**
     * Tests a MasterCard type of card
     */
    public function testMasterCard()
    {
        $this->assertTrue(LuhnChecker::check('5555555555554444')); // valid JCB Card
        $this->assertFalse(LuhnChecker::check('555555555555444'));
        $this->assertFalse(LuhnChecker::check('55555555555544444'));
    }

    /**
     * Tests a Visa type of card
     */
    public function testVisaCard()
    {
        $this->assertTrue(LuhnChecker::check('4111111111111111')); // valid JCB Card
        $this->assertFalse(LuhnChecker::check('411111111111111'));
        $this->assertFalse(LuhnChecker::check('41111111111111111'));
    }
}
