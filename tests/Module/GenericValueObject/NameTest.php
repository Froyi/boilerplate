<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Project\Module\GenericValueObject\Name;

/**
 * Class Test
 */
class NameTest extends TestCase
{
    /**
     * @dataProvider namesValidProvider
     *
     * @param $name
     */
    public function testNameIsValid($name): void
    {
        $this->assertSame($name, Name::fromString($name)->getName());
    }

    /**
     * @dataProvider namesTypeErrorProvider
     *
     * @expectedException TypeError
     * @param $name
     */
    public function testNamesTypeError($name): void
    {
        Name::fromString($name);
    }

    /**
     * @dataProvider namesInvalidProvider
     *
     * @expectedException InvalidArgumentException
     * @param $name
     */
    public function testNamesInvlidArgument($name): void
    {
        Name::fromString($name);
    }

    public function namesValidProvider(): array
    {
        return [
            ['Peter'],
            ['Al'],
            ['Hans-Peter'],
            ['Hans Peter'],
        ];
    }

    public function namesTypeErrorProvider(): array
    {
        return [
            [2],
            [true],
            [[]],
            [new stdClass()],
        ];
    }

    public function namesInvalidProvider(): array
    {
        return [
            ['a'],
            ['3948274sadiasdf']
        ];
    }
}
