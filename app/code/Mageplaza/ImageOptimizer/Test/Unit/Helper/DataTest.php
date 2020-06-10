<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     ${MODULENAME}
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\ImageOptimizer\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Driver\File as DriverFile;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\ImageOptimizer\Helper\Data;
use Mageplaza\ImageOptimizer\Model\Config\Source\Status;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\Collection as ImageOptimizerCollection;
use Mageplaza\ImageOptimizer\Model\ResourceModel\Image\CollectionFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class DataTest
 * @package Mageplaza\ImageOptimizer\Test\Unit\Helper
 */
class DataTest extends TestCase
{
    /**
     * @var Context|PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;
    /**
     * @var ObjectManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var StoreManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManager;

    /**
     * @var DriverFile|PHPUnit_Framework_MockObject_MockObject
     */
    protected $driverFile;

    /**
     * @var IoFile|PHPUnit_Framework_MockObject_MockObject
     */
    protected $ioFile;

    /**
     * @var Filesystem|PHPUnit_Framework_MockObject_MockObject
     */
    protected $filesystem;

    /**
     * @var CollectionFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactory;

    /**
     * @var CurlFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $curlFactory;

    /**
     * @var ManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_eventManager;

    /**
     * @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var State|PHPUnit_Framework_MockObject_MockObject
     */
    protected $state;

    /**
     * @var Data|PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperData;

    protected function setUp()
    {
        $this->context           = $this->getMockBuilder(Context::class)->disableOriginalConstructor()->getMock();
        $this->objectManager     = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->storeManager      = $this->getMockBuilder(StoreManagerInterface::class)->getMock();
        $this->driverFile        = $this->getMockBuilder(DriverFile::class)->disableOriginalConstructor()->getMock();
        $this->ioFile            = $this->getMockBuilder(IoFile::class)->disableOriginalConstructor()->getMock();
        $this->filesystem        = $this->getMockBuilder(Filesystem::class)->disableOriginalConstructor()->getMock();
        $this->collectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->curlFactory       = $this->getMockBuilder(CurlFactory::class)->disableOriginalConstructor()->getMock();
        $this->_eventManager     = $this->getMockBuilder(ManagerInterface::class)
            ->setMethods(['dispatch'])
            ->getMock();
        $this->scopeConfig       = $this->getMockBuilder(ScopeConfigInterface::class)->getMock();
        $this->context->method('getScopeConfig')->willReturn($this->scopeConfig);
        $this->state = $this->getMockBuilder(State::class)->disableOriginalConstructor()->getMock();
        $this->objectManager->method('get')
            ->with(State::class)
            ->willReturn($this->state);
        $this->state->method('getAreaCode')->willReturn('adminhtml');

        $this->helperData = new Data(
            $this->context,
            $this->objectManager,
            $this->storeManager,
            $this->driverFile,
            $this->ioFile,
            $this->filesystem,
            $this->curlFactory,
            $this->collectionFactory
        );
    }

    public function testAdminInstance()
    {
        $this->assertInstanceOf(Data::class, $this->helperData);
    }

    /**
     * @throws FileSystemException
     */
    public function testScanFiles()
    {
        $result      = [
            'path'        => 'pub/media/wysiwyg/home/home-eco.jpg',
            'status'      => Status::PENDING,
            'origin_size' => 82839
        ];
        $basePath    = '/var/www/html/tuvv/ce232';
        $directories = [$result['path']];
        $pathInfo    = [
            'extension' => 'jpg'
        ];
        $readInterface = $this->getMockBuilder(ReadInterface::class)->getMock();
        $collection = $this->getMockBuilder(ImageOptimizerCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filesystem->method('getDirectoryRead')->with(DirectoryList::ROOT)->willReturn($readInterface);
        $readInterface->method('getAbsolutePath')->willReturn($basePath);
        $this->collectionFactory->method('create')->willReturn($collection);
        $collection->method('getColumnValues')->with('path')->willReturn([]);

        $this->driverFile->method('isExists')->with($basePath)->willReturn(true);
        $this->driverFile->method('isReadable')
            ->with($basePath)
            ->willReturn(true);
        $this->driverFile->method('readDirectoryRecursively')
            ->with($basePath)
            ->willReturn($directories);
        $this->driverFile->method('isFile')
            ->with($result['path'])
            ->willReturn(true);
        $this->ioFile->method('getPathInfo')
            ->with($result['path'])
            ->willReturn($pathInfo);
        $this->driverFile->method('stat')
            ->with($result['path'])
            ->willReturn(['size' => 82839]);

        $this->assertEquals([$result], $this->helperData->scanFiles());
    }
}
