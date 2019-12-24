<?php
class OrviSoft_BundledProducts_Helper_Data extends Mage_Core_Helper_Abstract
{
    function getAllBundles($productID){
        $_product = Mage::getModel('catalog/product')->load($productID);
        $bundles = $_product->getData('bundles_assigned');
        if(!strlen($bundles)){
            return false;
        }
        $bundles = explode(",", $bundles);
        $_bundles = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('sku', array('in' => $bundles))->load();
        $output = array();
        foreach($_bundles as $bundle){
            $_bundle = Mage:getModel('catalog/product')->load($bundle->getId());
            $id = $bundle->getId();
            $parentProducts = $_bundle->getData('parent_products');
            if(!strlen($parentProducts)){
                $output[$id] = array();
                continue 1;
            }
            $parentProducts = explode(",", $parentProducts);
            $parentProducts = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('sku', array('in' => $parentProducts))->load();
            foreach($parentProducts as $parent){
                $output[$id][] = $$parent->getId();
            }
        }
        return $output;
    }

    function getProductById($productID){
        $_product = $_product = Mage::getModel('catalog/product')->load($productID);
        $output['image'] = Mage::helper('catalog/image')->init($_product, 'small_image')->resize(300);
        $output['name'] = $_product->getName();
        $output['product_url'] = $_product->getProductUrl();
        $output['price'] = $_product->getPrice();
        $output['final_price'] = $_product->getFinalPrice();
        $output['display_price'] = Mage::helper('checkout')->formatPrice($_product->getFinalPrice());
        return $output;
    }
}