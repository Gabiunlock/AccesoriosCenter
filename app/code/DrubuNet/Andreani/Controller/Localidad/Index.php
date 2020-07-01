<?php

namespace DrubuNet\Andreani\Controller\Localidad;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\TestFramework\Inspection\Exception;
use DrubuNet\Andreani\Model\CodigoPostalFactory;

/**
 * Class Localidad
 *
 * @description Action que recibe un id de provincia y devuelve todas las localidades que tenga con sus respectivos
 *              codigos postales.
 *
 * @author Mauro Maximiliano Martinez <mmartinez@ids.net.ar>
 * @package DrubuNet\Andreani\Controller\Localidad
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var CodigoPostalFactory
     */
    protected $_codigoPostalFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param CodigoPostalFactory $codigoPostalFactory
     */
    public function __construct
    (
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        CodigoPostalFactory $codigoPostalFactory
    )
    {
        $this->_resultPageFactory   = $resultPageFactory;
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_codigoPostalFactory = $codigoPostalFactory;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $request     = $this->getRequest();
        $result      = $this->_resultJsonFactory->create();
        $localidades = [];

        if(($provinciaId = $request->getParam('provincia_id')) && $request->isXmlHttpRequest())
        {
            $localidades = $this->_codigoPostalFactory->create()
                ->getCollection()
                ->addFieldToFilter('provincia_id',['eq'=>$provinciaId]);

            $localidades->getSelect()->group('localidad');
            $localidades = $localidades->getData();
        }

        return $result->setData($localidades);

    }
}