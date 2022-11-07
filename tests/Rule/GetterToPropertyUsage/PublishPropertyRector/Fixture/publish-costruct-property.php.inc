<?php

class SomeClass
{
    public function __construct(
        private $name,
    ) {
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
?>
-----
<?php

class SomeClass
{
    public function __construct(
        public $name,
    ) {
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
?>
