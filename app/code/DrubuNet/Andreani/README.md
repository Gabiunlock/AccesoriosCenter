# Módulo de envíos ANDREANI 2.3

## Requisitos

Para el correcto funcionamiento del módulo es necesario contar con:

- HTML2PDF

```
composer require spipu/html2pdf
```

```
Magento version >= 2.3 
```

## Otras versiones de Magento

  - Magento 1:  https://github.com/andreani-publico/magento
  - Magento 2.1:  https://github.com/andreani-publico/modulo-magento2
  - Magento 2.2:  https://github.com/andreani-publico/magento-2.2
  
  
  
## Instalación

Para concretar la instalacion del módulo es necesario realizar los siguientes pasos. Primeramente, parados en la carpeta root del proyecto:

1. Pegar las carpetas de app y pub sobre el root de magento. Combine las carpetas que ya existan.
2. Ejecute los siguientes comandos de magento:
```
	php bin/magento setup:upgrade
	php bin/magento cache:clean
```




## Configuración

1. En el admin de Magento, ingresar a Store -> Configuration -> Sales -> Shipping Methods
2. Configurar los contratos provistos por andreani 
3. En el admin de Magento, ingresar a Store -> Configuration -> Sales -> Shipping Settings
4. Configurar el numero de cliente y las credenciales
