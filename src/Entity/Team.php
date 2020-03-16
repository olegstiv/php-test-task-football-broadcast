<?php

namespace App\Entity;

class Team
{
    private string $name;
    private string $country;
    private string $logo;
    /**
     * @var Player[]
     */
    private array $players;
    private string $coach;
    private int $goals;

    public function __construct(string $name, string $country, string $logo, array $players, string $coach)
    {
        $this->assertCorrectPlayers($players);

        $this->name = $name;
        $this->country = $country;
        $this->logo = $logo;
        $this->players = $players;
        $this->coach = $coach;
        $this->goals = 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @return Player[]
     */
    public function getPlayersOnField(): array
    {
        return array_filter($this->players, function (Player $player) {
            return $player->isPlay();
        });
    }

    /**
     * @return Player[]
     */
    public function getPlayersOnBeach(): array
    {
        return array_filter($this->players, function (Player $player) {
            return $player->isBench();
        });
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(int $number): Player
    {
        foreach ($this->players as $player) {
            if ($player->getNumber() === $number) {
                return $player;
            }
        }

        throw new \Exception(
            sprintf(
                'Player with number "%d" not play in team "%s".',
                $number,
                $this->name
            )
        );
    }

    public function getCoach(): string
    {
        return $this->coach;
    }

    private function getPlayersFromPosition(string $position):array
    {
        $players = [];
        foreach ($this->getPlayers() as $player ){
            if ($player->getPosition() == ucfirst($position)){
                $players[] = $player;
            }
        }
        return $players;

    }

    public function addGoal(int $playNumber): void
    {
        $this->goals += 1;
        try {
            $this->getPlayer($playNumber)->addGoal();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getGoals(): int
    {
        return $this->goals;
    }

    public function getSumTimeFromPosition(string $position): int
    {
        $sumTime = 0;
        foreach ($this->getPlayersFromPosition($position) as $player ){
            $sumTime += $player->getOutMinute();
        }
        return $sumTime;

    }

    private function assertCorrectPlayers(array $players)
    {
        foreach ($players as $player) {
            if (!($player instanceof Player)) {
                throw new \Exception(
                    sprintf(
                        'Player should be instance of "%s". "%s" given.',
                        Player::class,
                        get_class($player)
                    )
                );
            }
        }
    }
}