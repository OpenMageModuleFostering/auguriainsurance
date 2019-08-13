<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Model_Sales_Order_Pdf_Total_Insurance extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Get array of arrays with totals information for display in PDF
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $label = Mage::helper('sales')->__($this->getTitle()) . ':';
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;

        $totals = array(); // multiple totals

        foreach ($this->getSource()->getItemsCollection() as $_item){
            if($_item instanceof Mage_Sales_Model_Order_Item){ // Order
                $productType = $_item->getProductType();
            }else{ // Invoice, Creditmemo
                $productType = $_item->getOrderItem()->getProductType();
            }

            if($productType == Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID){ // is insurance
                // add the insurance total
                $totals[] = array(
                    'amount'    => $this->getFormatedPrice($_item, $this->getSource()->getStore()),
                    'label'     => $_item->getName() . ' ' . Mage::helper('auguria_insurance')->__('(included in total amount)'),
                    'font_size' => $fontSize
                );
            }
        }

        return $totals;
    }

    /**
     * Get formated price with insurance configuration
     *
     * @param mixed $insurance
     * @return string
     */
    public function getFormatedPrice($insurance, $store)
    {
        $displayType = Mage::helper('auguria_insurance/config')->getInsuranceCartDisplayType();

        if($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX){ // Excl. Tax
            return $store->formatPrice($insurance->getPrice(), false);

        }elseif($displayType == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX){ // Incl. Tax
            return $store->formatPrice($insurance->getPriceInclTax(), false);

        }else{ // Both
            return $this->__('Excl. Tax ') . $store->formatPrice($insurance->getPrice(), false)
            . "\n" . $this->__('Incl. Tax ') . $store->formatPrice($insurance->getPriceInclTax(), false);
        }
    }
}