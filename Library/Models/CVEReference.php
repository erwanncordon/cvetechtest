<?php

namespace Cve\Models;

class CVEReference implements DataInterface
{
    /**
     * @var string $reference
     */
    public $reference;

    /**
     * @return string
     */
    public function getData() {
        return $this->reference;
    }
}