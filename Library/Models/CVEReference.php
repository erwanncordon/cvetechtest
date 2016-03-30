<?php

namespace Cve\Models;

class CVEReference implements DataInterface
{
    public $reference;

    public function getData() {
        return $this->reference;
    }
}