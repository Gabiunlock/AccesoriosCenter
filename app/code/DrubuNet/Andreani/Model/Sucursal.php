<?php


namespace DrubuNet\Andreani\Model;

use Magento\Framework\DataObject;
use DrubuNet\Andreani\Api\Data\SucursalInterface;

class Sucursal implements SucursalInterface
{
    private $name;
    private $location;
    private $id;
    private $postcode;
    private $code;
    private $region;
    private $street;
    private $price;
    /**
     * Gets the name.
     *
     * @api
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the location.
     *
     * @api
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Gets the id.
     *
     * @api
     * @return string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Gets the postcode.
     *
     * @api
     * @return string
     */
    public function getPostcode(){
        return $this->postcode;
    }

    /**
     * Gets the code.
     *
     * @api
     * @return string
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * Gets the region.
     *
     * @api
     * @return string
     */
    public function getRegion(){
        return $this->region;
    }

    /**
     * Gets the street.
     *
     * @api
     * @return string
     */
    public function getStreet(){
        return $this->street;
    }

    /**
     * Sets the name.
     *
     * @api
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets the location.
     *
     * @api
     * @param string
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Sets the id.
     *
     * @api
     * @param string
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Sets the postcode.
     *
     * @api
     * @param string
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * Sets the code.
     *
     * @api
     * @param string
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Sets the region.
     *
     * @api
     * @param string
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Sets the street.
     *
     * @api
     * @param string
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }
}