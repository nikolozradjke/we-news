<?php

namespace App\Command;

use App\Repository\NewsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class WeeklyNewsEmailCommand extends Command
{
    private const COMMAND_NAME = 'app:weekly-news-email';
    protected static $defaultName = 'app:weekly-news-email';
    protected static $defaultDescription = 'Send weekly email with top 10 most commented news';

    public function __construct(
        private NewsRepository $repo,
        private MailerInterface $mailer,
        private Environment $twig,
        private string $recipientEmail
    )
    {
        parent::__construct(self::COMMAND_NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $topNews = $this->repo->getTopMostCommentedNews();

            if (empty($topNews)) {
                $io->warning('No news found to send.');
                return Command::SUCCESS;
            }

            $this->sendWeeklyEmail($topNews);

            $io->success(sprintf('Weekly news email sent successfully to %s with %d articles.', 
                $this->recipientEmail, count($topNews)));

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Failed to send weekly news email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function sendWeeklyEmail(array $topNews): void
    {
        $htmlContent = $this->twig->render('emails/weekly_news.html.twig', [
            'news' => $topNews,
            'week' => date('W'),
            'year' => date('Y')
        ]);

        $email = (new Email())
            ->from('noreply@yoursite.com')
            ->to($this->recipientEmail)
            ->subject(sprintf('Weekly Top News - Week %s, %s', date('W'), date('Y')))
            ->html($htmlContent);

        $this->mailer->send($email);

        echo 'mail sent!';
    }
}
