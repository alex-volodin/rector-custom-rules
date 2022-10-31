<?php

class RightClass
{
    public function test()
    {
        return new A();
    }
    protected static function test2()
    {
        return new A();
    }
}
?>
