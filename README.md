# Unexpected DeliveryTime

Magento2 module delivery time for products.

## Requirements

* PHP >= **7.1**
* Magento >= **2.3**

## Installation

1. Module

    ```php
    composer require unexpected/delivery-time
    
    php bin/magento module:enable Unexpected_DeliveryTime
    
    php bin/magento setup:upgrade
    
    php bin/magento setup:di:compile
    
    php bin/magento setup:static-content:deploy -f
    ```

## Usage

#### **Stores->Configuration->Unexpected->Delivery Time**

* **General->Enabled** - module activation


* **Backend->Date unit** - select date unit
* **Backend->Slider min scale** - min slider value
* **Backend->Slider max scale** - max slider value 
* **Backend->Slider step scale** - slider step 


* **Frontend->Label** - delivery time label
* **Frontend->Sorting** - add delivery time to sorting options 
* **Frontend->Filters** - add delivery time to filter options
* **Frontend->Visible on** - choose on which pages delivery time should show

#### Catalog->Products

1. Simple product

    In Delivery Time tab select type on radiobox set.
    
    Next set up range on slider.

2. Configurable product

    Apply to simple products - select if You want to apply delivery time to all children products
    
    Apply from simple products - select if You want to choose delivery time from one of children products