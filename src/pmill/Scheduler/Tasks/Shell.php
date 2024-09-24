<?php

namespace pmill\Scheduler\Tasks;

class Shell extends TaskInterface
{
    /**
     * @var string
     */
    protected string $command;

    /**
     * @var array
     */
    protected array $arguments = [];

    /**
     * @return int
     */
    public function run(): int
    {
        $output = null;
        exec($this->getCommand() . ' ' . implode(' ', $this->arguments), $output, $result);

        $this->setOutput($output);
        return $result;
    }

    /**
     * @param string $command
     * @return $this
     */
    public function setCommand(string $command): Shell
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param mixed $argument
     * @return $this
     */
    public function addArgument($argument): Shell
    {
        $this->arguments[] = $argument;
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getTaskName(): string
    {
        return "Shell Task";
    }

    public function getTaskDescription(): string
    {
        return $this->command;
    }
}