<?php
declare(strict_types=1);

namespace App\UserInterface\Cli;

use App\Application\CommitQueryService;
use App\Domain\Commit\Exception\CommitResultException;
use App\Domain\Exception\InvalidVCSClientException;
use App\Domain\Repo\Exception\InvalidRepositoryException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LastCommitShaCommand extends Command {

    protected static $defaultName = 'app:git-remote-helper';

    /**
     * @var CommitQueryService
     */
    private $commitQueryService;

    public function __construct(CommitQueryService $commitQueryService) {
        $this->commitQueryService = $commitQueryService;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Get last repository commit sha.')
             ->addArgument("repository", InputArgument::REQUIRED, "Repository name: owner/repo-name")
             ->addArgument("branch", InputArgument::REQUIRED, "Repository branch")
             ->addOption("service", "s", InputArgument::OPTIONAL, "Repository service: [github(default)]");;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $repository = mb_split("/", $input->getArgument("repository"));
        $owner = $repository[0] ?? '';
        $repository = $repository[1] ?? '';
        if (empty($owner)) {
            $output->writeln("Owner cannot be empty");
        }
        if (empty($repository)) {
            $output->writeln("Repository cannot be empty");
        }

        $branch = $input->getArgument("branch");
        if (empty($branch)) {
            $output->writeln("Branch cannot be empty");

            return;
        }

        $service = $input->getOption("service");

        try {
            $commitDto = $this->commitQueryService->getLastCommitSha($owner, $repository, $branch, $service);
        } catch (InvalidRepositoryException $e) {
            $output->writeln($e->getMessage());

            return;
        } catch (InvalidVCSClientException $e) {
            $output->writeln("Unknown service $service");

            return;
        } catch (CommitResultException $e) {
            $output->writeln("Commit result error: {$e->getMessage()}");

            return;
        }

        $output->writeln($commitDto->getSha());
    }
}