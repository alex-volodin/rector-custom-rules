<?php

class RightClass
{
    private const SOME_CONST = 'test';
    protected const SOME_CONST_2 = 'test';
}
?>
-----
<?php

class RightClass
{
    public const SOME_CONST = 'test';
    public const SOME_CONST_2 = 'test';
}
?>
