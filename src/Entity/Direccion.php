<?php

namespace App\Entity;

use App\Repository\DireccionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DireccionRepository::class)
 */
class Direccion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $calle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    //como es nulable al crear la dirrecion en el postman no hace falta ponerlo
    private   $puerta_piso_escalera;


    /**
     * @ORM\Column(type="integer")
     */
    private $cod_postal;

    /**
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="direcciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Municipios::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $municipio;

    /**
     * @ORM\ManyToOne(targetEntity=Provincias::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $provincia;

    public function getId(): ?int
    {
        return $this->id;
    }

    //los dos puntos y el ? es el retorno. En vez de hacerse  con los /** y el @ return  */ Al final es lo mismo
    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): self
    {
        $this->calle = $calle;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPuertaPisoEscalera(): ?string
    {
        return $this->puerta_piso_escalera;
    }

    public function setPuertaPisoEscalera(string $puerta_piso_escalera): self
    {
        $this->puerta_piso_escalera = $puerta_piso_escalera;

        return $this;
    }

    public function getCodPostal(): ?int
    {
        return $this->cod_postal;
    }

    public function setCodPostal(int $cod_postal): self
    {
        $this->cod_postal = $cod_postal;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getMunicipio(): ?Municipios
    {
        return $this->municipio;
    }

    public function setMunicipio(Municipios $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getProvincia(): ?Provincias
    {
        return $this->provincia;
    }

    public function setProvincia(Provincias $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }
}
