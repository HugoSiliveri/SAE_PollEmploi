<?php

namespace App\AgoraScript\Model\DataObject;

class NombreVote extends AbstractDataObject
{
    private int $idProposition;
    private int $nbTB;
    private int $nbBB;
    private int $nbAB;
    private int $nbPP;
    private int $nbII;
    private int $nbAR;

    /**
     * @param int $idProposition
     * @param int $nbTB
     * @param int $nbBB
     * @param int $nbAB
     * @param int $PP
     * @param int $nbII
     * @param int $nbAR
     */
    public function __construct(int $idProposition, int $nbTB, int $nbBB, int $nbAB, int $nbPP, int $nbII, int $nbAR)
    {
        $this->idProposition = $idProposition;
        $this->nbTB = $nbTB;
        $this->nbBB = $nbBB;
        $this->nbAB = $nbAB;
        $this->nbPP = $nbPP;
        $this->nbII = $nbII;
        $this->nbAR = $nbAR;
    }

    public function nbTotal(): int
    {
        return $this->nbTB + $this->nbBB + $this->nbAB + $this->nbPP + $this->nbII + $this->nbAR;
    }

    /**
     * @return int
     */
    public function getIdProposition(): int
    {
        return $this->idProposition;
    }

    /**
     * @param int $idProposition
     */
    public function setIdProposition(int $idProposition): void
    {
        $this->idProposition = $idProposition;
    }

    /**
     * @return int
     */
    public function getNbTB(): int
    {
        return $this->nbTB;
    }

    /**
     * @param int $nbTB
     */
    public function setNbTB(int $nbTB): void
    {
        $this->nbTB = $nbTB;
    }

    /**
     * @return int
     */
    public function getNbBB(): int
    {
        return $this->nbBB;
    }

    /**
     * @param int $nbBB
     */
    public function setNbBB(int $nbBB): void
    {
        $this->nbBB = $nbBB;
    }

    /**
     * @return int
     */
    public function getNbAB(): int
    {
        return $this->nbAB;
    }

    /**
     * @param int $nbAB
     */
    public function setNbAB(int $nbAB): void
    {
        $this->nbAB = $nbAB;
    }

    /**
     * @return int
     */
    public function getNbPP(): int
    {
        return $this->nbPP;
    }

    /**
     * @param int $PP
     */
    public function setPP(int $PP): void
    {
        $this->PP = $PP;
    }

    /**
     * @return int
     */
    public function getNbII(): int
    {
        return $this->nbII;
    }

    /**
     * @param int $nbII
     */
    public function setNbII(int $nbII): void
    {
        $this->nbII = $nbII;
    }

    /**
     * @return int
     */
    public function getNbAR(): int
    {
        return $this->nbAR;
    }

    /**
     * @param int $nbAR
     */
    public function setNbAR(int $nbAR): void
    {
        $this->nbAR = $nbAR;
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idProposition,
            "nbTBTag" => $this->nbTB,
            "nbBBTag" => $this->nbBB,
            "nbABTag" => $this->nbAB,
            "nbPPTag" => $this->nbPP,
            "nbIITag" => $this->nbII,
            "nbARTag" => $this->nbAR
        );
    }

    public function getNombreVoteFormatTableau(): array
    {
        return array($this->nbAR, $this->nbII, $this->nbPP, $this->nbAB, $this->nbBB, $this->nbTB);
    }
}