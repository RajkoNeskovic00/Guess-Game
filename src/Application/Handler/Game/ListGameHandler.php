<?php

namespace Guess\Application\Handler\Game;

use Exception;
use DateTimeImmutable;
use Guess\Domain\League\League;

use Guess\Domain\Game\GameRepositoryInterface;
use Guess\Infrastructure\Doctrine\GameRepository;
use Guess\Domain\League\LeagueRepositoryInterface;
use Guess\Application\Filter\Game\FilterByLeagueNameSluggedTrait;

class ListGameHandler
{
    use FilterByLeagueNameSluggedTrait;
    private GameRepositoryInterface $gameRepository;
    private LeagueRepositoryInterface $leagueRepository;


    public function __construct( GameRepositoryInterface $gameRepository,LeagueRepositoryInterface $leagueRepository)
    {
        
        $this->gameRepository=$gameRepository;
        $this->leagueRepository=$leagueRepository;
    
    }

    /**
     * @param string $week
     * @param string $leagueNameSlugged
     * @return array
     * @throws Exception
     */
    public function handle(string $week, string $leagueNameSlugged): array
    {
        /** @var League $league */
        $league = $this->leagueRepository->findOneBy([
            'leagueNameSlugged' => $leagueNameSlugged
        ]);

        if (!$league) {
            throw new Exception('Which league matches you want to see?');
        }

        $gamesForGivenWeek = $this->gameRepository->findGamesForGivenWeek(
            new DateTimeImmutable(
                $week ? $week." week" : "now"
            )
        );

        return $this->filter($gamesForGivenWeek, $league->getLeagueNameSlugged());
    }
}