<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Class Test
 */
class NameTest extends TestCase
{
    /**
     * @dataProvider namesValidProvider
     */
    public function testNameIsValid($name)
    {
        $this->assertSame($name, \Project\Module\GenericValueObject\Name::fromString($name)->getName());
    }

    /**
     * @dataProvider namesTypeErrorProvider
     *
     * @expectedException TypeError
     */
    public function testNamesTypeError($name)
    {
        \Project\Module\GenericValueObject\Name::fromString($name);
    }

    /**
     * @dataProvider namesInvalidProvider
     *
     * @expectedException InvalidArgumentException
     */
    public function testNamesInvlidArgument($name)
    {
        \Project\Module\GenericValueObject\Name::fromString($name);
    }

    public function namesValidProvider()
    {
        return [
            ['Peter'],
            ['Al'],
            ['Hans-Peter'],
            ['Hans Peter'],
        ];
    }

    public function namesTypeErrorProvider()
    {
        return [
            [2],
            [true],
            [[]],
            [new stdClass()],
        ];
    }

    public function namesInvalidProvider()
    {
        return [
            ['a'],
            ['3948274sadiasdf']
        ];
    }
}
