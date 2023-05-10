<?php

namespace banana;

interface PdoInterface
{
    public function setConnection(\PDO $connection);
}