<?php

/* Copyright (C) 2015 Michael Giesler, Stephan Kreutzer
 *
 * This file is part of Dembelo.
 *
 * Dembelo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Dembelo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License 3 for more details.
 *
 * You should have received a copy of the GNU Affero General Public License 3
 * along with Dembelo. If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * @package DembeloMain
 */

namespace DembeloMain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Exception;

/**
 * Class User
 *
 * @MongoDB\Document
 * @MongoDBUnique(fields="email")
 */
class User implements UserInterface, \Serializable, AdvancedUserInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     */
    protected $password;

    /**
     * @MongoDB\Collection
     * @Assert\NotBlank()
     */
    protected $roles;

    /**
     * @MongoDB\ObjectId
     */
    protected $licenseeId;

    /**
     * @MongoDB\ObjectId
     */
    protected $currentTextnode;

    /**
     * @MongoDB\String
     */
    protected $gender;

    /**
     * @MongoDB\String
     */
    protected $source;

    /**
     * @MongoDB\String
     */
    protected $reason;

    /**
     * @MongoDB\Int
     * @Assert\NotBlank()
     */
    protected $status;

    /**
     * @MongoDB\String
     */
    protected $activationHash;

    /**
     * @MongoDB\Hash
     */
    protected $metadata;

    /**
     * @MongoDB\ObjectId
     */
    protected $lastTopicId;

    /**
     * gets the mongodb id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the mongoDB id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * gets the email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * sets the usermail, used for security
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * sets the email used as username
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * gets the password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * sets the password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * from UserInterface, not needed for our encoder
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * gets the user's roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * sets the roles
     *
     * @param array $roles
     */
    public function setRoles($roles)
    {
        if (!is_array($roles)) {
            $roles = array($roles);
        }
        $this->roles = $roles;
    }

    /**
     * Gets the last textnode ID of topic \p $topicId the user was reading.
     *
     * @return string|null Textnode ID or null, if there wasn't a current
     *     textnode ID set so far.
     */
    public function getCurrentTextnode()
    {
        return $this->currentTextnode;
    }

    /**
     * Saves the ID of the textnode the user is currently
     *     reading.
     *
     * @param string $textnodeId ID of the textnode the user is
     *     currently reading.
     */
    public function setCurrentTextnode($textnodeId)
    {
        $this->currentTextnode = $textnodeId;
    }

    /**
     * from UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * serializes the object
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
            $this->currentTextnode,
        ));
    }

    /**
     * unserializes the object
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
            $this->currentTextnode,
            ) = unserialize($serialized);
    }

    /**
     * sets the licensee id
     *
     * @param string $id licensee ID
     */
    public function setLicenseeId($id)
    {
        $this->licenseeId = $id;
    }

    /**
     * gets the licensee id
     *
     * @return string
     */
    public function getLicenseeId()
    {
        return $this->licenseeId;
    }

    /**
     * sets the gender
     *
     * @param string $gender Gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * gets the gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * gets the source from where the user came to this site
     *
     * @return String
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * sets the source from where the user came to this site
     *
     * @param String $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * gets the reason for registration
     *
     * @return String
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * sets the reason for registration
     *
     * @param String $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * gets status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * sets status
     *
     * @param integer $status status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * gets activation hash
     *
     * @return mixed
     */
    public function getActivationHash()
    {
        return $this->activationHash;
    }

    /**
     * sets activation hash
     *
     * @param String $hash activation hash
     */
    public function setActivationHash($hash)
    {
        $this->activationHash = $hash;
    }

    /**
     * checks if account is not expired
     *
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * checks if account is not locked
     *
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * checks if credentials are not expired
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * checks if enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->status === 1;
    }

    /**
     * gets the metadata
     * @return Array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * sets the metadata
     * @param Array|string $metadata
     * @param string       $value
     *
     * @throws Exception
     */
    public function setMetadata($metadata, $value = null)
    {
        if (is_array($metadata) && is_null($value)) {
            $this->metadata = $metadata;
        } elseif (is_string($metadata) && !is_null($value)) {
            $this->metadata[$metadata] = $value;
        } else {
            throw new Exception('invalid data');
        }
    }

    /**
     * sets the last topic id this user selected
     *
     * @param string $lastTopicId
     */
    public function setLastTopicId($lastTopicId)
    {
        $this->lastTopicId = $lastTopicId;
    }

    /**
     * gets the last topic id this user selected
     * @return string
     */
    public function getLastTopicId()
    {
        return $this->lastTopicId;
    }
}
