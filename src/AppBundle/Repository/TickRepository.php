<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TickRepository
 * 
 * @category SymfonyBundle
 * @package  AppBundle\Repository
 * @author   Jesús Flores <jesusfloressanjose@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GNU General Public License
 */
class TickRepository extends EntityRepository
{
    public function getLast(){
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tick t ORDER BY t.date DESC')
            ->setMaxResults(1)
            ->getResult();
    }
} 