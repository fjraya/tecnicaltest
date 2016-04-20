<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 13:28
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/dal/BaseDAO.php";
class BaseDAOTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    /**
     * method doCommand
     * when calledWithErrorInExecute
     * should throw
     * @expectedException DomainException
     */
    public function test_doCommand_calledWithErrorInExecute_throw()
    {
        list($sqliteStub, $sqlite3StmtStub) = $this->configureSQLiteStubs();
        $sqlite3StmtStub->expects($this->any())->method("execute")->will($this->throwException(new DomainException()));
        $this->exerciseDoCommand($sqliteStub);
    }

    /**
     * method doCommand
     * when calledWithFalseInExecute
     * should throw
     * @expectedException Exception
     * @expectedExceptionMessage Error en doCommand
     */
    public function test_doCommand_calledWithFalseInExecute_throw()
    {
        list($sqliteStub, $sqlite3StmtStub) = $this->configureSQLiteStubs();
        $sqlite3StmtStub->expects($this->any())->method("execute")->will($this->returnValue(false));
        $this->exerciseDoCommand($sqliteStub);
    }

    /**
     * @return array
     */
    private function configureSQLiteStubs()
    {
        $sqliteStub = $this->getMockBuilder("SQLite3")->disableOriginalConstructor()->getMock();
        $sqlite3StmtStub = $this->getMockBuilder("SQLite3Stmt")->disableOriginalConstructor()->getMock();
        $sqliteStub->expects($this->any())->method("prepare")->will($this->returnValue($sqlite3StmtStub));
        $sqlite3StmtStub->expects($this->any())->method("bindValue")->will($this->returnValue(null));
        return array($sqliteStub, $sqlite3StmtStub);
    }

    /**
     * @param $sqliteStub
     */
    private function exerciseDoCommand($sqliteStub)
    {
        $baseDAO = new BaseDAO("dummy", $sqliteStub);
        $baseDAO->doCommand("dummySql");
    }


}