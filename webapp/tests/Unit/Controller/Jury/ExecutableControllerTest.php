<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Jury;

use App\Entity\Executable;

class ExecutableControllerTest extends JuryControllerTest
{
    protected static $baseUrl          = '/jury/executables';
    protected static $exampleEntries   = ['adb','run','boolfind comparator'];
    protected static $shortTag         = 'executable';
    protected static $deleteEntities   = ['description' => ['adb']];
    protected static $getIDFunc        = 'getExecid';
    protected static $className        = Executable::class;
    protected static $DOM_elements     = ['h1' => ['Executables']];
    protected static $addForm          = 'executable_upload[';
    protected static $addEntitiesShown = ['shortname','name'];
    protected static $addEntities      = [];/*['type' => 'compare',
                                           'archives' => ''],
                                          ['type' => 'compile',
                                           'archives' => ''],
                                          ['type' => 'run',
                                           'archives' => '']];*/
}
