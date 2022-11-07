<?php

class SomeClass
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }
}
?>
