<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Data Entity.
 *
 * @property int $id
 * @property string $lti_user_consumer_key
 * @property string $lti_user_context_id
 * @property \App\Model\Entity\LtiUser $lti_user
 * @property string $lti_user_user_id
 * @property \App\Model\Entity\LtiUserUser $lti_user_user
 * @property string $data
 * @property int $revision_parent
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Data extends Entity
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
        '*' => true,
        'id' => false,
    ];
}
