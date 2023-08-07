<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HomeController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->ThreadsTable = TableRegistry::getTableLocator()->get("threads");
    }

    public function index()
    {
        $title = "掲示板";
        $this->set('threads',$this->ThreadsTable->getList());
        $this->set('title',$title);
    }

    public function thread($id)
    {
        dd($this->ThreadsTable->getData($id));

    }
}
