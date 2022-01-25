<?php


namespace Stru\StruHyperfOauth;


use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;

class Scope implements Arrayable,Jsonable
{
    public $id;
    public $description;

    public function __construct($id,$description)
    {
        $this->id = $id;
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description
        ];
    }

    public function toJson($optons = 0)
    {
        return json_encode($this->toArray(),$optons);
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}