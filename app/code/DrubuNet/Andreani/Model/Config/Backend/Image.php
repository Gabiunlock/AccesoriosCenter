<?php

namespace DrubuNet\Andreani\Model\Config\Backend;
use Magento\Config\Model\Config\Backend\Image as ImageModel;

/**
 * Class Image
 * @description Modelo de configuración que habilita la carga de la imagen del logo de la empresa.
 * @author Drubu Team
 * @package DrubuNet\Andreani\Helper
 * @package DrubuNet\Andreani\Model\Config\Backend
 */
class Image extends ImageModel
{

    const UPLOAD_DIR = 'andreani/logo_empresa'; // Folder donde se almacenará la imagen.

    /**
     * Devuelve el path del directorio que guardará la imagen.
     *
     * @return string
     * @throw \Magento\Framework\Exception\LocalizedException
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    /**
     * Makes a decision about whether to add info about the scope.
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    /**
     * Extensiones permitidas
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }
}