<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Block_Sales_Order_Total_Insurance extends Mage_Core_Block_Template
{
    protected $_source;
    protected $_items = array();
    protected $_colspan = 4;
    protected $_colspanCreditmemo = 6;
    protected $_additionnalAttributes;
    protected $_additionnalStyles;
    protected $_store;

    /**
     * Get data (totals) source model
     *
     * @return Varien_Object
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_source = $parent->getSource();
        $this->_store = Mage::app()->getStore($this->_source->getStoreId());

        if ($this->_source && $this->_hasInsurances($this->_source)) {
            $this->_addInsurance();
        }

        return $this;
    }

    protected function _hasInsurances($source)
    {
        foreach ($source->getItemsCollection() as $_item){
            if($_item instanceof Mage_Sales_Model_Order_Item){ // Order
                $productType = $_item->getProductType();
            }else{ // Invoice, Creditmemo
                $productType = $_item->getOrderItem()->getProductType();
            }

            if($productType == Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID){ // is insurance
                $this->_items[] = $_item;
            }
        }

        return $this->_items;
    }

    /**
     * Add insurance total string
     *
     * @param string $after
     * @return Auguria_Insurance_Block_Sales_Order_Insurance
     */
    protected function _addInsurance($after = 'last')
    {
        $amount = 1;

        $total = new Varien_Object(array(
                'code'          => 'auguria_insurance',
                'field'         => 'auguria_insurance_amount',
                'block_name'    => $this->getNameInLayout(),
                'value'         => $amount,
                'label'         => $this->helper('auguria_insurance')->__('Insurances')
        ));

        $this->getParentBlock()->addTotal($total, $after);

        return $this;
    }

    public function getTotal()
    {
        return $this->getParentBlock()->getTotal('auguria_insurance');
    }

    public function getInsurances()
    {
        return $this->_items;
    }

    /**
     * Set colspan (in layout)
     *
     * @param string $code
     * @param int $number
     */
    public function setColspan($code = 'default', $number)
    {
        if($code == 'default'){
            $this->_colspan = $number;
        }elseif($code == 'creditmemo'){
            $this->_colspanCreditmemo = $number;
        }
    }

    public function getColspan()
    {
        if($this->_source instanceof Mage_Sales_Model_Order_Creditmemo){
            return $this->_colspanCreditmemo;
        }

        return $this->_colspan;
    }

    public function setAdditionnalAttributes($attributes)
    {
        $this->_additionnalAttributes = $attributes;
    }

    public function getAdditionnalAttributes()
    {
        return $this->_additionnalAttributes;
    }

    public function setAdditionnalStyles($styles)
    {
        $this->_additionnalStyles = $styles;
    }

    public function getAdditionnalStyles()
    {
        return $this->_additionnalStyles;
    }

    /**
     * Get formated price with insurance configuration
     *
     * @param mixed $insurance
     * @return string
     */
    public function getFormatedPrice($insurance)
    {
        $displayType = Mage::helper('auguria_insurance/config')->getInsuranceCartDisplayType();

        if($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX){ // Excl. Tax
            return $this->_store->formatPrice($insurance->getPrice());

        }elseif($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX){ // Incl. Tax
            return $this->_store->formatPrice($insurance->getPriceInclTax());

        }else{ // Both
            return $this->__('Excl. Tax ') . $this->_store->formatPrice($insurance->getPrice())
            . '<br>' . $this->__('Incl. Tax ') . $this->_store->formatPrice($insurance->getPriceInclTax());
        }
    }
}