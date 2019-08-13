<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get all insurances
     *
     * @return array
     */
    public function getInsuranceIds()
    {
        return Mage::getResourceModel('catalog/product_collection')
        ->addAttributeToFilter('type_id', Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID)
        ->addStoreFilter()
        ->getAllIds();
    }

    public function setInsuranceExcluded($id, $value)
    {
        Mage::getSingleton('checkout/session')->setData("auguria_insurance_{$id}_excluded", $value);
    }

    public function getInsuranceExcluded($id)
    {
        return Mage::getSingleton('checkout/session')->getData("auguria_insurance_{$id}_excluded");
    }
}