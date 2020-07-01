<?php


namespace DrubuNet\Andreani\Api\Data;

/**
 * Sucursal Interface
 */
interface SucursalInterface
{
    /**
     * @api
     * @return string
     */
    public function getName();

    /**
     * @api
     * @return string
     */
    public function getLocation();

    /**
     * @api
     * @return string
     */
    public function getId();

    /**
     * @api
     * @return string
     */
    public function getPostcode();

    /**
     * @api
     * @return string
     */
    public function getCode();

    /**
     * @api
     * @return string
     */
    public function getRegion();

    /**
     * @api
     * @return string
     */
    public function getStreet();

    /**
     * @api
     * @param string
     * @return void
     */
    public function setName($name);

    /**
     * @api
     * @param string
     * @return void
     */
    public function setLocation($location);

    /**
     * @api
     * @param string
     * @return void
     */
    public function setId($id);

    /**
     * @param string
     * @return void
     */
    public function setPostcode($postcode);

    /**
     * @api
     * @param string
     * @return void
     */
    public function setCode($code);

    /**
     * @api
     * @param string
     * @return void
     */
    public function setRegion($region);

    /**
     * @api
     * @param string
     * @return void
     */
    public function setStreet($street);
}