<?php


namespace Shershon\Common\Util;


class AlarmMessageEvent
{

    protected $summary;

    protected $description;

    protected $code;

    public function __construct(string $summary, string $description, ?string $code = null)
    {
        $this->summary     = $summary;
        $this->description = $description;
        $this->code        = $code;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

}