<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Model_Observer
{
    /**
     * Update quote qty informations
     *
     * Event : sales_quote_collect_totals_after
     *
     * @param Varien_Event_Observer $observer
     * @return Auguria_Insurance_Model_Observer
     */
    public function initQuoteQty(Varien_Event_Observer $observer)
    {
        /* @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $insuranceCount = 0;
        $insuranceQty = 0;

        // get insurance product type qty
        foreach ($quote->getAllVisibleItems() as $_item){
            if($_item->getProductType() == Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID){
                $insuranceCount ++;
                $insuranceQty += $_item->getQty();
            }
        }

        // update quote qty informations
        $quote->setItemsCount($quote->getItemsCount() - $insuranceCount);
        $quote->setItemsQty((float) $quote->getItemsQty() - $insuranceQty);

        return $this;
    }

    /**
     * Update item qty
     *
     * Event : sales_quote_item_qty_set_after
     *
     * @param Varien_Event_Observer $observer
     * @return Auguria_Insurance_Model_Observer
     */
    public function initItemQty(Varien_Event_Observer $observer)
    {
        /* @var Mage_Sales_Model_Quote_Item $item */
        $item = $observer->getEvent()->getItem();

        if($item->getProductType() == Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID){
            $item->setData('qty', 1); // force qty (bug when reorder if not forced)
        }

        return $this;
    }

    /**
     * Init insurances in quote
     *
     * Event : controller_action_predispatch_checkout_cart_index
     *
     * @param Varien_Event_Observer $observer
     * @return Auguria_Insurance_Model_Observer
     */
    public function initQuoteInsurances(Varien_Event_Observer $observer)
    {
        /* @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getSingleton('checkout/cart')->getQuote();

        foreach (Mage::helper('auguria_insurance')->getInsuranceIds() as $_id){
            if(!Mage::helper('auguria_insurance')->getInsuranceExcluded($_id)){

                try {
                    $_insurance = Mage::getModel('catalog/product')->load($_id);
                    if($_insurance->getAuguriaInsuranceIsDefault()){ // insurance is added by default
                        $quote->addProduct($_insurance);
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
    }
}