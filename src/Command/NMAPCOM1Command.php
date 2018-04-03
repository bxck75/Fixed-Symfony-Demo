<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Cocur\BackgroundProcess\BackgroundProcess;

class NMAPCOM1Command extends Command
{
    protected static $defaultName = 'NMAP_COM_1';
    

    protected function configure()
    {

        $this
            ->setDescription('Random scan N ip`s For open port 80')
            ->setName('app:nmap-random-scan')
            ->addArgument('count', InputArgument::OPTIONAL, 'How many random ip`s ')
            ->addArgument('ports', InputArgument::OPTIONAL, 'Ports in csv (80,21,22,etc)')
            ->addOption('output', null, InputOption::VALUE_NONE, 'Output True/False')
            ->setHelp('This command Runs a nmap scan on N random ip`s for open ports...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');
        $ports = $input->getArgument('ports');

        if ($count) {
            $io->note(sprintf('You will Scan %s Random IP`s', $count));
        }else{
            echo "Only 100 random IP`s are beeing scanned!";
            $count =100;
        }
        if($ports) {
            $io->note(sprintf('Folowing ports are scanned: %s', $ports));
        }else {
            echo "Only port 80 is beeing scanned!";
            $ports = 80;
        }
  
        if ($input->getOption('output')) {
            // ...
        }
        $append = TRUE;
        $outputfile = 'nmaprandomscan1_output'.date("y-m-d-h-i-s").'.lst';
        //var_dump($io);
        $io->success('Scanning with nmap! Pass --help to see your options.');
        //$this->command = sprintf('nmap -iR %s -Pn -p %s -n -vvv --open |grep "Discovered"',$count,$ports);
        $ready2exec = sprintf('%s %s %s 2>&1 ', sprintf('nmap -iR %s -Pn -p %s -n -vvv --open |grep "Discovered"',$count,$ports), ($append) ? '>>' : '>', $outputfile);
 
        $Result=$this->Processie($ready2exec);
        var_dump($Result);

    }
    
    public function Processie($command2run = ' nmap -iR 100 -Pn -p 80 -n -vvv --open |grep "Discovered" ') {

        $ErrOutput = array();
        $Output = array();
        $process = new Process($command2run);
        //$process = new Process('ls -lsa');
        $process->start();

        foreach ($process as $type => $data) {

            if ($process::OUT === $type) {
                $Output[] = "\n====> ".$data;
            } else { // $process::ERR === $type
                $ErrOutput[] = "\n=====> ".$data;
            }

        } 
    return $Output;
    }
}
