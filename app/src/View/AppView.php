<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
    }

    public function enFormat($num)
    {
        return '¥' . number_format($num);
    }

    public function isAdminUser($users)
    {
        return isset($users) && $users->authority === 1;
    }

    public function publicDeleteCommentButton($comment,$users)
    {
        if(isset($users)){
            if($comment->user_id === $users->id || $this->isAdminUser($users)){
                return true;
            }
        }else{
            if($comment->user_id === NULL){
                return true;
            }
        }
        return false;
    }
    
    public function publicDeleteButton($thread,$users)
    {
        if(isset($users)){
            if($thread->user_id === $users->id || $this->isAdminUser($users)){
                return true;
            }
        }else{
            if($thread->user_id === NULL){
                return true;
            }
        }
        return false;
    }
}
