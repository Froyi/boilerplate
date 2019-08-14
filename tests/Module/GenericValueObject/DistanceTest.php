<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Project\Module\GenericValueObject\Distance;

/**
 * Class Test
 */
class DistanceTest extends TestCase
{
    /**
     * @dataProvider distanceValidProvider
     *
     * @param $distance
     * @param $isMeter
     * @param $result
     */
    public function testDistanceIsValid($distance, $isMeter, $result): void
    {
        $this->assertSame($result, Distance::fromValue($distance, $isMeter)->getDistance());
    }

    public function distanceValidProvider(): array
    {
        return [
            [3, true, 3],
            ['3', true, 3],
            ['0.3', true, 0],
            [0, true, 0],
            ['0.5', true, 1],
            [1207876, true, 1207876],
            [4, false, 4000],
            ['4', false, 4000],
            ['0.4', false, 400],
        ];
    }
}
