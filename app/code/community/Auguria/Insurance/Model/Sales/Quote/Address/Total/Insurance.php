<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_Model_Sales_Quote_Address_Total_Insurance extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $title = Mage::helper('auguria_insurance')->__('Insurances');

        $address->addTotal(array(
            'code'    => $this->getCode(),
            'title'   => $title,
            'value'   => 1,
            'style'   => 'insurance',
            'area'    => 'footer',
        ));

        return $this;
    }

}
