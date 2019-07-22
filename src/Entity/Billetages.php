<?php
/**
 * Created by PhpStorm.
 * User: Mouni
 * Date: 22/11/2016
 * Time: 10:39
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;


class Billetages
{
    Const SEP_LINE='|', SEP_COL='X';

    private $billetageLignes;
    private $valeurTotal;
    private $stringDetail;

    public function __construct()
    {
        $this->billetageLignes=new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getBilletageLignes()
    {
        return $this->billetageLignes;
    }

    /**
     * @param ArrayCollection $billetageLignes
     * @return Billetages
     */
    public function setBilletageLignes($billetageLignes)
    {
        $this->billetageLignes = $billetageLignes;
        return $this;
    }

    public function addBilletageLigne(BilletageLignes $billetageLigne)
    {
        //dump($billetageLigne->getNbBillet());dump($billetageLigne->getValeurBillet());
        $this->valeurTotal=$this->valeurTotal+$billetageLigne->getTotalLigne();
        $this->billetageLignes->add($billetageLigne);
        return $this;

    }

    public function removeBilletageLigne(BilletageLignes $billetageLigne)
    {
        $this->valeurTotal=$this->valeurTotal-$billetageLigne->getTotalLigne();
        $this->billetageLignes->removeElement($billetageLigne);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValeurTotal()
    {
        return $this->valeurTotal;
    }


    /**
     * @return $this
     */
    public function calcValeurTotal()
    {
        $this->valeurTotal=0;
        $this->stringDetail='';
        foreach ($this->getBilletageLignes() as $billetageLigne){
            if ($billetageLigne->getNbBillet()){
                $this->valeurTotal= $this->valeurTotal+$billetageLigne->getTotalLigne();
                $this->stringDetail= ($this->stringDetail)
                    ?$this->stringDetail.$this::SEP_LINE.$billetageLigne->getNbBillet().$this::SEP_COL.$billetageLigne->getValeurBillet()
                    :$this->stringDetail.$billetageLigne->getNbBillet().$this::SEP_COL.$billetageLigne->getValeurBillet();
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStringDetail()
    {
        return $this->stringDetail;
    }

    public function setBilletageLignesFromText($detailBilletage, $billets=false){
        $billetageLignesFromTexts=explode($this::SEP_LINE,$detailBilletage);

        if($billets) {
            $this->setBilletageLignesFromBillets($billets);
            foreach ($billetageLignesFromTexts as $billetageLignesFromText) {
                $cols = explode($this::SEP_COL, $billetageLignesFromText);

                $billetExist=false;
                if (count($cols) > 1) {
                    foreach ($this->getBilletageLignes() as $billetageLigne) {
                        if ($billetageLigne->getValeurBillet() == $cols[1]) {
                            $billetageLigne->setNbBillet($cols[0]);
                            $billetExist=true;
                        }
                    }
                }
                //Billet de ligneBilletageTexs n'exitant plus
                if (!$billetExist){
                    $billetageLigne=new BilletageLignes();
                    $billetageLigne->setNbBillet($cols[0]);
                    $billetageLigne->setValeurBillet($cols[1]);
                    $this->addBilletageLigne($billetageLigne);
                }
            }
        }else{
            foreach ($billetageLignesFromTexts as $billetageLignesFromText) {
                $cols = explode($this::SEP_COL, $billetageLignesFromText);
                $billetageLigne=new BilletageLignes();
                $billetageLigne->setNbBillet($cols[0]);
                $billetageLigne->setValeurBillet($cols[1]);
                $this->addBilletageLigne($billetageLigne);
            }
        }
    }

    public function setBilletageLignesFromBillets($billets){
        foreach ($billets as $billet){
            $billetageLigne=new BilletageLignes();
            $billetageLigne->setValeurBillet($billet->getValeur());
            $this->addBilletageLigne($billetageLigne);
        }
    }



}