<?php

namespace App\Entity;

use JsonSchema\Exception\ResourceNotFoundException;

class Player
{
    public const PLAY_PLAY_STATUS = 'play';
    public const BENCH_PLAY_STATUS = 'bench';

    public const NULL_CARD_TYPE = 0;
    public const RED_CARD_TYPE = 1;
    public const YELLOW_CARD_TYPE = 2;

    public const FORWARD_POSITION_TYPE = 'Н';
    public const MIDFIELDER_POSITION_TYPE = 'П';
    public const GOALKEEPER_POSITION_TYPE = 'В';
    public const DEFENDER_POSITION_TYPE = 'З';

    public const POSITION_TYPES = [
        self::FORWARD_POSITION_TYPE => 'Нападающий',
        self::MIDFIELDER_POSITION_TYPE => 'Полузащитник',
        self::GOALKEEPER_POSITION_TYPE => 'Вратарь',
        self::DEFENDER_POSITION_TYPE => 'Защитник',
    ];

    private int $number;
    private string $name;
    private string $position;
    private string $playStatus;
    private int $inMinute;
    private int $outMinute;
    private int $hasCard;
    private int $goals;

    public function __construct(int $number, string $name, string $position)
    {
        $this->number = $number;
        $this->name = $name;
        $this->position = self::getTypePosition($position);
        $this->playStatus = self::BENCH_PLAY_STATUS;
        $this->inMinute = 0;
        $this->outMinute = 0;
        $this->goals = 0;
        $this->hasCard = self::NULL_CARD_TYPE;
    }

    public static function getTypePosition(string $position): string
    {
        $position = ucfirst($position);
        if (array_key_exists($position, self::POSITION_TYPES))
            return $position;

        throw new \Exception(
            sprintf('incorrect position "%s"', $position)
        );
    }

    public function getPosition()
    {
        return $this->position;
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

    public function isBench(): bool
    {
        return $this->playStatus === self::BENCH_PLAY_STATUS;
    }

    public function getPlayTime(): int
    {
        if (!$this->outMinute) {
            return 0;
        }
        return $this->outMinute - $this->inMinute;
    }

    public function addMinute($minute, string $where):void
    {
        if ($where == self::PLAY_PLAY_STATUS)
            $this->outMinute += $minute;
        if ($where == self::BENCH_PLAY_STATUS)
            $this->inMinute+= $minute;;
    }

    public function goToPlay(int $minute): void
    {
        $this->playStatus = self::PLAY_PLAY_STATUS;
    }

    public function goToBench(int $minute): void
    {
        $this->playStatus = self::BENCH_PLAY_STATUS;
    }

    public function addYellowCard(int $minute): void
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
                throw new \Exception(
                    sprintf('The player "%s" already has a red card', $this->getName())
                );
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