<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * Company Entity
 *
 * @property int $id
 * @property string|null $thread
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Admin $admin
 */
class Thread extends Entity
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
        'id' => false
    ];

    public function getUrl()
    {
        return Router::url(['controller' => 'Comment', 'action' => 'comment', 'id' => $this->id], false);
    }

    public function getdeta()
    {
        $modified = $this->modified;
        return $modified->format('Y/m/d H:i:s');
    }
    
}
