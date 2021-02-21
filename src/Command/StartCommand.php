<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class StartCommand extends Command
{
    protected static $defaultName = 'app:start';
    protected static $defaultDescription = 'Command run dedicated specificed algorithm.';
    //protected static $path = $this->get('kernel')->getProjectDir() . '\public\data\\'; 
    //protected $path = $this->getParameter('kernel.project_dir') . '\public\data\\';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('file', InputArgument::OPTIONAL, 'Set correct file name.')
        ;
    }

    protected function generateFileLocation(string $file): string
    {
        return self::$path.$file.'.csv';
    }

    private function processFile(string $fileName)
    {
        //input file validation
        $filesystem = new Filesystem();

        echo $this->get('kernel')->getProjectDir();

        //create or overwrite raport files
        $files = ['correct', 'uncorrect','raport'];
        foreach ($files as $file) {
            if (!$filesystem->exists($this->generateFileLocation($file))) {
                $filesystem->touch($this->generateFileLocation($file));
            } else {
                file_put_contents($this->generateFileLocation($file), "");
            }
        }

        //read and parse data
        if ($filesystem->exists($this->generateFileLocation($fileName))) {
            $file = file_get_contents($this->generateFileLocation($fileName));
            $emails = explode(PHP_EOL, $file);

            foreach ($emails as $email) {
                $validator = new EmailValidator();
                $checkEmail = $validator->isValid($email, new RFCValidation());
                $fileName = $checkEmail ? 'correct' : 'uncorrect';
                $filesystem->appendToFile($this->generateFileLocation($fileName), $email.PHP_EOL);
                $filesystem->appendToFile($this->generateFileLocation('raport'), $email.';'.$fileName.PHP_EOL);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if ($file) {
            $this->processFile($file);
        }

        $io->success('Algorithm proceded successfully. Files exported to:"SYMFONY"/src/Command/');

        return Command::SUCCESS;
    }
}
