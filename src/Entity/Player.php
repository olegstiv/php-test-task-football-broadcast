<?php

namespace App\Entity;

use JsonSchema\Exception\ResourceNotFoundException;

class Player
{
    private const PLAY_PLAY_STATUS = 'play';
    private const BENCH_PLAY_STATUS = 'bench';
    public const NULL_CARD_TYPE = 0;
    public const RED_CARD_TYPE = 1;
    public const YELLOW_CARD_TYPE = 2;

    private int $number;
    private string $name;
    private string $playStatus;
    private int $inMinute;
    private int $outMinute;
    private int $hasCard;
    private int $goals;

    public function __construct(int $number, string $name)
    {
        $this->number = $number;
        $this->name = $name;
        $this->playStatus = self::BENCH_PLAY_STATUS;
        $this->inMinute = 0;
        $this->outMinute = 0;
        $this->goals = 0;
        $this->hasCard = self::NULL_CARD_TYPE;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInMinute(): int
    {
        return $this->inMinute;
    }

    public function getOutMinute(): int
    {
        return $this->outMinute;
    }

    public function isPlay(): bool
    {
        return $this->playStatus === self::PLAY_PLAY_STATUS;
    }

    public function getPlayTime(): int
    {
        if (!$this->outMinute) {
            return 0;
        }

        return $this->outMinute - $this->inMinute;
    }

    public function goToPlay(int $minute): void
    {
        $this->inMinute = $minute;
        $this->playStatus = self::PLAY_PLAY_STATUS;
    }

    public function goToBench(int $minute): void
    {
        $this->outMinute = $minute;
        $this->playStatus = self::BENCH_PLAY_STATUS;
    }

    public function addYellowCard(int $minute)
    {
        switch ($this->hasCard) {
            case self::NULL_CARD_TYPE:
                $this->hasCard = self::YELLOW_CARD_TYPE;
                break;
            case self::YELLOW_CARD_TYPE:
                $this->hasCard = self::RED_CARD_TYPE;
                $this->goToBench($minute);
                break;
            default:
                return new ResourceNotFoundException('The player already has a red card', 403);
        }
    }

    public function getCard(): int
    {
        return $this->hasCard;
    }

    public function addGoal(): void
    {
        $this->goals++;
    }

    public function getGoals(): int
    {
        return $this->goals;
    }
}