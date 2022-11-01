<?php

class WrongClass
{
    public string $value;
    public const SOME_CONST = 'test';

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    static function someConst(): self
    {
        return new \WrongClass(self::SOME_CONST);
    }

    public function test()
    {
        $a = \WrongClass::someConst();
    }

    public function test2()
    {
        echo \WrongClass::someConst()->getValue();
    }
}
?>
