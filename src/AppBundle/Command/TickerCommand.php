<?php
/*
 * This file is part of the Mundoreader Symfony Base package.
 *
 * (c) Mundo Reader S.L.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TickerCommand
 * 
 * @category SymfonyBundle
 * @package  AppBundle\Command
 * @author   Jesús Flores <jesus.flores@bq.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 * @link     http://bq.com
 */
class TickerCommand extends ContainerAwareCommand
{

    protected $logger; // monolog logger
    protected $em; // entity manager

    protected function configure()
    {

        $this
            ->setName('api:ticker')
            ->setDescription('Get fresh ticker data from blockchain.info')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger = $this->getContainer()->get('logger');
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        var_dump($this->fetchApiData());
    }

    protected function fetchApiData(){
        $apiBaseUrl = 'https://blockchain.info/ticker';
        $this->logger->debug('Getting api url: '.$apiBaseUrl);
        $jsonData = file_get_contents($apiBaseUrl);
        return json_decode($jsonData,TRUE);
    }
} 