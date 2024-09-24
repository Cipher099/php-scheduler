<?php
namespace pmill\Scheduler\Interfaces;

use DateTime;

interface TaskInterface
{
    public function run();

    public function getExpression();

    public function getOutput();

    public function isDue();
}
