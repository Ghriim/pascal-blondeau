<?php

namespace PBlondeau\Bundle\WorkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PBlondeau\Bundle\CommonBundle\Entity\BaseEntity;
use PBlondeau\Bundle\UserBundle\Entity\User;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="PBlondeau\Bundle\WorkBundle\Repository\PhotoRepository")
 *
 * @ORM\HasLifecycleCallbacks
 */
class Photo extends BaseEntity
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
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="PBlondeau\Bundle\WorkBundle\Entity\Album")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $album;

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
     * @param Album $album
     *
     * @return $this
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
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
        return 'uploads/albums/' . $this->getAlbum()->getId();
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

        $dir = $this->getUploadRootDir();
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        $this->file->move($dir, $this->path);
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
}
