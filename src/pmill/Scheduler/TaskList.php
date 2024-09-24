<?php

namespace pmill\Scheduler;

use pmill\Scheduler\Tasks\Task;

class TaskList
{
    /**
     * @var array<Task>
     */
    protected array $tasks = [];

    /**
     * @var array<string>
     */
    protected array $output = [];

    /**
     * Adds a new task to the list
     *
     * @param Task $task
     * @return TaskList $this
     */
    public function addTask(Task $task): TaskList
    {
        $this->tasks[] = $task;
        return $this;
    }

    /**
     * @param array<Task> $tasks
     */
    public function setTasks(array $tasks): void
    {
        $this->tasks = $tasks;
    }

    /**
     * @return array<Task>
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @return array<Task>
     * */
    public function getTasksDue(): array
    {
        return array_filter($this->tasks, function (Task $task) {
            return $task->isDue();
        });
    }

    /**
     * @return array
     */
    public function getOutput(): array
    {
        return $this->output;
    }

    /**
     * Runs any due task, returning an array containing the output from each task
     *
     * @return array
     */
    public function run(): array
    {
        $this->output = [];

        foreach ($this->getTasksDue() as $task) {
            if ($task->runCriteria() != null &&
                !$task->runCriteria()) {
                continue;
            }
            $result = $task->run();
            $this->output[] = [
                'task' => get_class($task),
                'result' => $result,
                'output' => $task->getOutput(),
            ];
        }

        return $this->output;
    }
}
