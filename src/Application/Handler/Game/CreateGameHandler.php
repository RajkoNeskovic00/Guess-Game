<?php

namespace Guess\Application\Handler\Game;

use Exception;
use DateTimeImmutable;
use Guess\Domain\Game\Game;

use Guess\Domain\Game\GameRepositoryInterface;
use Guess\Domain\Team\TeamRepositoryInterface;
use Guess\Domain\League\LeagueRepositoryInterface;

class CreateGameHandler
{

    private GameRepositoryInterface $gameRepository;
        private TeamRepositoryInterface $teamRepository;
        private LeagueRepositoryInterface $leagueRepository;

    public function __construct(
         GameRepositoryInterface $gameRepository,
         TeamRepositoryInterface $teamRepository,
        LeagueRepositoryInterface $leagueRepository
    )
    {
        $this->teamRepository=$teamRepository;
        $this->gameRepository=$gameRepository;
        $this->leagueRepository=$leagueRepository;


    }

    /**
     * @param array $game
     * @throws Exception
     */
    public function handle(array $game): void
    {
        $homeTeam = $this->teamRepository->findOneBy(['name' => $game['homeTeam']]);
        $awayTeam = $this->teamRepository->findOneBy(['name' => $game['awayTeam']]);
        $league = $this->leagueRepository->findOneBy(['leagueApiId' => $game['leagueApiId']]);

        if (!$homeTeam) {
            throw new Exception($game['awayTeam']." is not the part of our database");
        }

        if (!$awayTeam) {
            throw new Exception($game['awayTeam']." is not the part of our database");
        }

        if (!$league) {
            throw new Exception($game['leagueApiId']." League is not the part of our database");
        }

        $gameTime = new DateTimeImmutable($game['gameTime']);

        $this->gameRepository->save(
            (new Game())
                ->setHomeTeam($homeTeam)
                ->setLeague($league)
                ->setAwayTeam($awayTeam)
                ->setGameTime($gameTime)
        );
    }
}