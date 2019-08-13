<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Block_Checkout_Insurance extends Mage_Catalog_Block_Product
{
    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Check if the cart is empty
     *
     * @return bool
     */
    public function isNotEmptyCart()
    {
        return (bool)Mage::getSingleton('checkout/session')->getQuote()->getItemsCount();
    }

    /**
     * Get assurances associated to the current store
     *
     * return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getInsurances()
    {
        return Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('short_description')
            ->addAttributeToFilter('type_id', Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID)
            ->addStoreFilter()
            ->addPriceData();
    }

    /**
     * Check if insurance is in cart
     *
     * @param Mage_Catalog_Model_Product $insurance
     * @return bool
     */
    public function isInsuranceInCart(Mage_Catalog_Model_Product $insurance)
    {
        $items = $this->_getCart()->getItems();

        if ($items && $items->getSize()) {
            foreach ($items as $item) { // compare all items in cart
                if ($item->getProductId() == $insurance->getId()) {
                    return true; // insurance exists in cart
                }
            }
        }

        return false;
    }

    /**
     * FOR 1.8 BUG (get special price attribute in price template)
	 * bug in catalog/product/price.phml line 50
     *
     * Retrieve attribute instance by name, id or config node
     *
     * If attribute is not found false is returned
     *
     * @param string|integer|Mage_Core_Model_Config_Element $attribute
     * @return Mage_Eav_Model_Entity_Attribute_Abstract || false
     */
    public function getProductAttribute($attribute)
    {
        return $this->getProduct()->getResource()->getAttribute($attribute);
    }

}
