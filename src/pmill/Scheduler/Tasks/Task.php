<?php

namespace pmill\Scheduler\Tasks;

use Cron\CronExpression;
use DatePeriod;
use DateTime;
use Exception;
use pmill\Scheduler\Interfaces\TaskInterface as TaskInterface;

abstract class Task implements TaskInterface
{
    /**
     * @var string
     */
    protected string $expression;

    /**
     * @var null|string|array
     */
    protected ?mixed $output;

    /**
     * The name of the task for visualization
     * @var
     */
    abstract public function getTaskName(): string;

    /**
     * A short description of the task
     * @return mixed
     */
    abstract public function getTaskDescription(): string;

    /**
     * Check the criteria of the job before running it
     * @return true if job/task should run
     */
    abstract public function runCriteria(): ?bool;

    /**
     * Checks two dates and whether the current is between them,
     * @param DateTime|null $start the start date
     * @param DateTime|null $end the end date
     * @return bool if the event is between the dates
     * @throws Exception when the start date is after the end date
     */
    protected function betweenDates(?DateTime $start, ?DateTime $end): bool
    {
        $todayDate = new DateTime();
        if ($start->getTimestamp() > $end->getTimestamp()) {
            throw new Exception('Start date must be BEFORE end date');
        }
        return $todayDate->getTimestamp() > $start->getTimestamp() &&
            $todayDate->getTimestamp() < $end->getTimestamp();
    }

    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * Sets a cron expression
     * @param string $expression the cron job expression
     * @return TaskInterface $this
     */
    public function setExpression(
        string $expression
    ): TaskInterface
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * Gets the current cron expression
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * Sets the output from the task
     * @param null|string|array $output
     * @return TaskInterface $this
     */
    public function setOutput($output): TaskInterface
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Gets the output from the task
     * @return null|string|array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Checks whether the task is currently due
     * @return bool
     */
    public function isDue(): bool
    {
        $expression = $this->getExpression();
        if (!$expression) {
            return false;
        }

        $cron = CronExpression::factory($expression);
        return $cron->isDue();
    }

}