<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Jury;

use App\Entity\User;

class UserControllerTest extends JuryControllerTest
{
    protected static $baseUrl          = '/jury/users';
    protected static $exampleEntries   = ['admin','judgehost','Administrator','team'];
    protected static $shortTag         = 'user';
    protected static $deleteEntities   = ['username' => ['demo']];
    protected static $getIDFunc        = 'getUserid';
    protected static $className        = User::class;
    protected static $DOM_elements     = ['h1' => ['Users']];
    protected static $addForm          = 'user[';
    protected static $addEntitiesShown = ['name', 'username','email'];
    protected static $addEntities      = [['username' => 'newuser',
                                         'name' => 'New User',
                                         'plainPassword' => 'passwordpassword'],
                                         ['username' => 'ipauth',
                                         'name' => 'Auth via IP',
                                         'ipAddress' => '10.10.10.10',
                                         'plainPassword' => 'passwordpassword']];
}
