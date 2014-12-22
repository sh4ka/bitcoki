<?php


namespace AppBundle\Command;

use AppBundle\Entity\Tick;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TickerCommand
 * 
 * @category SymfonyBundle
 * @package  AppBundle\Command
 * @author   Jesús Flores <jesusfloressanjose@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License

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
        $this->processTickAlerts($this->fetchApiData());
    }

    protected function processTickAlerts($tickData){
        $alerts = $this->em->getRepository('AppBundle:Alert')->findBy(['enabled' => true]);
        $currentLast = $tickData['USD']['last'];
        $tick = new Tick();
        $tick->setDate(new \DateTime());
        $tick->setValue($currentLast);
        $this->em->persist($tick);
        $this->em->flush();
        foreach($alerts as $alert){
            if($alert->getMin() > $currentLast){
                $this->getContainer()->get('mailer')->send($this->getLowMsg($alert, $currentLast));
                $alert->setEnabled(false);
                $this->em->persist($alert);
                $this->em->flush();
            } elseif($alert->getMax() < $currentLast){
                $this->getContainer()->get('mailer')->send($this->getHighMsg($alert, $currentLast));
                $alert->setEnabled(false);
                $this->em->persist($alert);
                $this->em->flush();
            }
        }
    }

    protected function getLowMsg($alert, $current){
        return \Swift_Message::newInstance()
            ->setSubject('Bitcoki Alert')
            ->setFrom($this->getContainer()->getParameter('alert_mail_from'))
            ->setTo($alert->getEmail())
            ->setBody(
                'Current bitcoin price of '.$current.
                ' USD is lower than your alert. This alert is now deleted, you can recreate it by clicking this link '.
                $alert->getHash()
            )
        ;
    }

    protected function getHighMsg($alert, $current){
        return \Swift_Message::newInstance()
            ->setSubject('Bitcoki Alert')
            ->setFrom($this->getContainer()->getParameter('alert_mail_from'))
            ->setTo($alert->getEmail())
            ->setBody(
                'Current bitcoin price of '.$current.
                ' USD is higher than your alert. This alert is now deleted, you can recreate it by clicking this link '.
                $alert->getHash()
            )
        ;
    }

    protected function fetchApiData(){
        $apiBaseUrl = 'https://blockchain.info/ticker';
        $this->logger->debug('Getting api url: '.$apiBaseUrl);
        $jsonData = file_get_contents($apiBaseUrl);
        return json_decode($jsonData,TRUE);
    }
} 