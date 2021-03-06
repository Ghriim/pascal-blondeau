<?php

namespace PBlondeau\Bundle\PressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use PBlondeau\Bundle\UserBundle\Entity\User;

/**
 * PressArticle
 *
 * @ORM\Table(name="press_article")
 * @ORM\Entity(repositoryClass="PBlondeau\Bundle\PressBundle\Repository\PressArticleRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class PressArticle extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(1)
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime")
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $creation;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="PBlondeau\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @Assert\File(
     *      maxSize="6000000",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png"}
     * )
     * @Assert\NotNull(groups={"creation"})
     */
    public $file;

    public function __construct()
    {
        $this->creation = new \DateTime();
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return PressArticle
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return PressArticle
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     * @return $this
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        if($status == BaseEntity::STATUS_STOPPED) {
            $this->setPosition(null);
        }

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/pressArticles';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);
        $this->path = $this->file->getClientOriginalName();
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();

        if ($file && file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return is_null($this->getId());
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        return $this->status == BaseEntity::STATUS_STOPPED;
    }
}
