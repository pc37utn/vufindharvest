<?php

/**
 * OAI-PMH harvester console runner unit test.
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2016.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind
 * @package  Tests
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development
 */
namespace VuFindTest\Harvest\OaiPmh;

use VuFindHarvest\OaiPmh\HarvesterConsoleRunner;

/**
 * OAI-PMH harvester console runner unit test.
 *
 * @category VuFind
 * @package  Tests
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development
 */
class HarvesterConsoleRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get mock harvester object
     *
     * @return \VuFindHarvest\OaiPmh\Harvester
     */
    protected function getMockHarvester()
    {
        return $this->getMockBuilder('VuFindHarvest\OaiPmh\Harvester')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test basic functionality of console runner
     *
     * @return void
     */
    public function testRunFromIniFile()
    {
        $basePath = '/foo/bar';
        $client = $this->getMock('Zend\Http\Client');
        $harvester = $this->getMockHarvester();
        $expectedSettings = [
            'url' => 'http://bar',
            'metadataPrefix' => 'oai_dc',
            'from' => null,
            'until' => null,
            'silent' => false
        ];
        $factory = $this->getMock('VuFindHarvest\OaiPmh\HarvesterFactory');
        $factory->expects($this->once())
            ->method('getHarvester')
            ->with(
                $this->equalTo('foo'), $this->equalTo($basePath),
                $this->equalTo($client), $this->equalTo($expectedSettings)
            )
            ->will($this->returnValue($harvester));
        $opts = HarvesterConsoleRunner::getDefaultOptions();
        $ini = realpath(__DIR__ . '/../../../../fixtures/test.ini');
        $opts->setArguments(['--ini=' . $ini]);
        $runner = new HarvesterConsoleRunner(
            $opts, $client, $basePath, $factory, true
        );
        $this->assertTrue($runner->run());
    }
}