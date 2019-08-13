<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Block_Checkout_Cart_Total_Insurance extends Mage_Checkout_Block_Total_Default
{
    protected $_insurances = null;

    /**
     * Custom template
     *
     * @var string
     */
    protected $_template = 'auguria/insurance/checkout/cart/total/insurance.phtml';

    /**
     * Get products insurances
     *
     * @return array
     */
    public function getInsurances()
    {
        if(is_null($this->_insurances)){
            $this->_insurances = array();

            foreach ($this->getQuote()->getItemsCollection() as $_item){
                /* @var $_item Mage_Sales_Model_Quote_Item*/
                if($_item->getProductType() == Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID){
                    $this->_insurances[] = $_item;
                }
            }
        }

        return $this->_insurances;
    }

    /**
     * Get formated price with insurance configuration
     *
     * @param Mage_Sales_Model_Quote_Item $insurance
     * @return string
     */
    public function getFormatedPrice(Mage_Sales_Model_Quote_Item $insurance)
    {
        $displayType = Mage::helper('auguria_insurance/config')->getInsuranceCartDisplayType();

        if($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX){ // Excl. Tax
            return Mage::helper('checkout')->formatPrice($insurance->getPrice());

        }elseif($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX){ // Incl. Tax
            return Mage::helper('checkout')->formatPrice($insurance->getPriceInclTax());

        }else{ // Both
            return $this->__('Excl. Tax ') . Mage::helper('checkout')->formatPrice($insurance->getPrice())
                            . '<br>' . $this->__('Incl. Tax ') . Mage::helper('checkout')->formatPrice($insurance->getPriceInclTax());
        }
    }

}
