<?php
namespace App\Command;

use Cake\Console\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

class RemoveCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {


        $this->CommentTable = TableRegistry::getTableLocator()->get("comments");
        $this->CommentTable->removeComment();
        //$io->out('Hello, CakePHP Console!');
    }
}