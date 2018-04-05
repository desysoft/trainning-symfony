<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OC\platformBundle\Purge;

// N'oubliez pas ce use
use Doctrine\ORM\QueryBuilder;

/**
 * Description of Purger
 *
 * @author DESYSOFT
 */
class OCPurger {

    //put your code here
    //private $doctrine;
    private $em;
    private $int_days;

    public function __construct($doctrine) {
        $this->em = $doctrine->getManager();
    }

    public function purge($days) {
        $i = 0;
        $qb = $this->getEm()->createQueryBuilder()
                ->select('a')
                ->from('OCplatformBundle:Advert', 'a')
                ->innerJoin('a.applications', 'app', 'WITH', 'DATE_DIFF(CURRENT_DATE(),a.updatedAt)>' . $days);
        $listAdvert = $qb->getQuery()->getResult();
        foreach ($listAdvert as $advert) {
            if (count($advert->getApplications) == 0) {
                $this->removeAllCategory($advert);
                $this->getEm()->remove($advert);
                $this->flush();
                $i++;
            }
        }
        return $i;
    }

    private function removeAllCategories(\OC\platformBundle\Entity\Advert $advert) {
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }
    }

    function getEm() {
        return $this->em;
    }

    function setEm($em) {
        $this->em = $em;
    }

}
