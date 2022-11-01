<?php

class RightClass
{
    public const TEST = 'test';
    public static function test()
    {
        return new A();
    }
    public function test2()
    {
    }
}
?>
-----
<?php

class RightClass
{
    public const TEST = 'test';
    public function test2()
    {
    }
}
?>
