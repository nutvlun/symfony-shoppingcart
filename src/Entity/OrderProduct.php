<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderProductRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository", repositoryClass=OrderProductRepository::class)
 */
class OrderProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",name="order_id")
     */
    private $orderId;

    /**
     * @ORM\Column(type="integer",name="product_id")
     * @Groups({"show_bill_detail"})
     */
    private $productId;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_bill_detail"})
     */
    private $quantity;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",name="created_at" ,nullable=true)
     */
    private DateTime $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",name="updated_at" ,nullable=true)
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="float",name="unit_price")
     */
    private $unitPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orderProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

   /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
