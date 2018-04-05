<?php

namespace OC\platformBundle\Repository;
//namespace OC\platformBundle\Entity;

use Doctrine\ORM\EntityRepository;
/**
 * ApplicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends EntityRepository
{
    public function getApplicationWithAdvert($limit){
        $qb= $this->createQueryBuilder("a");
        
        $qb->innerJoin('a.advert', 'adv')->addSelect('adv');
        
        $qb->setMaxResults($limit);
        
        return $qb->getQuery()->getResult();
    }
}