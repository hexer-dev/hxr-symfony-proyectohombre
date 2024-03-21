<?php

namespace App\Filter;

class ReportFiler
{
    private $dateStart;

    private $dateEnd;

    private $program;

    public function __construct()
    {
        $now = new \DateTime('NOW');
        $start = $now->format('Y') . '-01-01';

        $this->setDateStart(new \DateTime($start));
        $this->setDateEnd($now);
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }
}
