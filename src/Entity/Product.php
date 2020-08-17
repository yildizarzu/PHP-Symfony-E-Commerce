<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categoryid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subcategoryid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urunadi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marka;

    /**
     * @ORM\Column(type="float")
     */
    private $fiyat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stok;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $durum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $teknikozellikler;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $digerozellikler;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryid(): ?string
    {
        return $this->categoryid;
    }

    public function setCategoryid(string $categoryid): self
    {
        $this->categoryid = $categoryid;

        return $this;
    }

    public function getSubcategoryid(): ?string
    {
        return $this->subcategoryid;
    }

    public function setSubcategoryid(string $subcategoryid): self
    {
        $this->subcategoryid = $subcategoryid;

        return $this;
    }

    public function getUrunadi(): ?string
    {
        return $this->urunadi;
    }

    public function setUrunadi(string $urunadi): self
    {
        $this->urunadi = $urunadi;

        return $this;
    }

    public function getMarka(): ?string
    {
        return $this->marka;
    }

    public function setMarka(string $marka): self
    {
        $this->marka = $marka;

        return $this;
    }

    public function getFiyat(): ?float
    {
        return $this->fiyat;
    }

    public function setFiyat(float $fiyat): self
    {
        $this->fiyat = $fiyat;

        return $this;
    }

    public function getStok(): ?string
    {
        return $this->stok;
    }

    public function setStok(string $stok): self
    {
        $this->stok = $stok;

        return $this;
    }

    public function getDurum(): ?string
    {
        return $this->durum;
    }

    public function setDurum(string $durum): self
    {
        $this->durum = $durum;

        return $this;
    }

    public function getTeknikozellikler(): ?string
    {
        return $this->teknikozellikler;
    }

    public function setTeknikozellikler(string $teknikozellikler): self
    {
        $this->teknikozellikler = $teknikozellikler;

        return $this;
    }

    public function getDigerozellikler(): ?string
    {
        return $this->digerozellikler;
    }

    public function setDigerozellikler(string $digerozellikler): self
    {
        $this->digerozellikler = $digerozellikler;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
