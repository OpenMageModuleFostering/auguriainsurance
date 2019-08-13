<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Helper_Config extends Mage_Core_Helper_Abstract
{
    /* PATH TO CONFIGURATION */
    const XML_PATH_INSURANCE_ACTIVE                = 'auguria_insurance/general/active';

    const XML_PATH_INSURANCE_CART_DISPLAY_PRICE    = 'auguria_insurance/cart/display_price';

    const XML_PATH_INSURANCE_SALES_DISPLAY_PRICE   = 'auguria_insurance/sales/display_price';

    /**
     * Check if insurance extension is active for the current store
     *
     * @return bool
     */
    public function isInsuranceActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_INSURANCE_ACTIVE);
    }

    /**
     * Check if cart price must be displayed Excl. Tax, Incl. Tax...
     *
     * @return int
     */
    public function getInsuranceCartDisplayType()
    {
        return Mage::getStoreConfig(self::XML_PATH_INSURANCE_CART_DISPLAY_PRICE);
    }

    /**
     * Check if sales price must be displayed Excl. Tax, Incl. Tax...
     *
     * @return int
     */
    public function getInsuranceSalesDisplayType()
    {
        return Mage::getStoreConfig(self::XML_PATH_INSURANCE_SALES_DISPLAY_PRICE);
    }
}