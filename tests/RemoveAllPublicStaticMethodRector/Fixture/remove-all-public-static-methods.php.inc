<?php

class RightClass
{
    public static function test()
    {
        return new A();
    }
}
?>
-----
<?php

class RightClass
{
}
?>
