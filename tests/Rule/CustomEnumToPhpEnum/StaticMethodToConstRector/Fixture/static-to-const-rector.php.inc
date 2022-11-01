<?php

class RightClass
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
        return new \RightClass(self::SOME_CONST);
    }

    public function test()
    {
        $a = \RightClass::someConst();
    }

    public function test2()
    {
        echo \RightClass::someConst()->getValue();
    }
}
?>
-----
<?php

class RightClass
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
        return new \RightClass(self::SOME_CONST);
    }

    public function test()
    {
        $a = \RightClass::SOME_CONST;
    }

    public function test2()
    {
        echo \RightClass::SOME_CONST;
    }
}
?>
