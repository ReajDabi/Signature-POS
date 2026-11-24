<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_adds_without_float_drift(): void
    {
        $this->assertSame('0.30', Money::add('0.10', '0.20'));
        $this->assertSame('99.99', Money::sub('100.00', '0.01'));
    }

    public function test_it_multiplies_exactly(): void
    {
        $this->assertSame('1050.00', Money::mul('350.00', '3'));
    }

    public function test_percent_rounds_half_up(): void
    {
        $this->assertSame('52.50', Money::percent('350.00', '15.00'));
        $this->assertSame('3.33', Money::percent('33.33', '10.00'));
        $this->assertSame('0.01', Money::percent('0.05', '10.00')); // 0.005 rounds up
    }

    public function test_negative_values_round_away_from_zero(): void
    {
        $this->assertSame('-1.24', Money::round('-1.235'));
    }

    public function test_compare(): void
    {
        $this->assertSame(0, Money::compare('1.50', '1.5'));
        $this->assertSame(1, Money::compare('2.00', '1.99'));
    }
}
