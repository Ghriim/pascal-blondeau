<?php

namespace PBlondeau\Bundle\WorkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use PBlondeau\Bundle\UserBundle\Entity\User;

/**
 * Album
 *
 * @ORM\Table(name="album")
 * @ORM\Entity(repositoryClass="PBlondeau\Bundle\WorkBundle\Repository\AlbumRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Album extends BaseEntity
{
    const OPENED_COUNT_DEFAULT = 0;

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
     * @ORM\Column(name="title", type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     */
    private $title;

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
     * @var integer
     * @ORM\Column(name="opened_count", type="integer", nullable=false)
     * @Assert\GreaterThanOrEqual(0)
     *
     */
    private $openedCount = self::OPENED_COUNT_DEFAULT;

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
     * @var Photo[]
     *
     * @ORM\OneToMany(targetEntity="PBlondeau\Bundle\WorkBundle\Entity\Photo", mappedBy="album", indexBy="id")
     */
    private $photos;

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
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return $this
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
     * @return $this
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
     * @return int
     */
    public function getOpenedCount()
    {
        return $this->openedCount;
    }

    /**
     * @param int $openedCount
     */
    public function setOpenedCount($openedCount)
    {
        $this->openedCount = $openedCount;
    }

    /**
     * Increase Opened Count by one
     */
    public function incrementOpenedCount()
    {
        $this->openedCount++;
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

    /**
     * @return Photo[]
     */
    public function getPhotos()
    {
        return $this->photos;
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
        return 'uploads/albums';
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
