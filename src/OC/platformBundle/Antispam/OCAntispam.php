<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace OC\platformBundle\Antispam;

class OCAntispam {

    /**
     * Vérifie si le texte est un spam ou non
     *
     * @param string $text
     * @return bool
     */
    private $mailer;
    private $locale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minLength) {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;
    }

    public function isSpam($text) {
        return strlen($text) < $this->minLength;
    }

}
