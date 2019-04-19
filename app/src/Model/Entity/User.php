<?php
namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $email
 * @property string|null $api_key
 * @property string|null $api_key_plain
 * @property string|null $token
 * @property \Cake\I18n\FrozenTime|null $token_expires
 * @property bool $is_superuser
 * @property string|null $role
 * @property bool $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property int|null $created_by
 * @property int|null $modified_by
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'first_name' => true,
        'last_name' => true,
        'password' => true,
        'email' => true,
        'api_key' => true,
        'api_key_plain' => true,
        'token' => true,
        'token_expires' => true,
        'is_superuser' => true,
        'role' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'created_by' => true,
        'modified_by' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];

    protected function _setPassword($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }
}
