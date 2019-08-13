<?php
/**
 * @category   Auguria
 * @package    Auguria_Insurance
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

class Auguria_Insurance_IndexController extends Mage_Core_Controller_Front_Action
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
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Initialize product instance from request data
     *
     * @return Mage_Catalog_Model_Product || false
     */
    protected function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('insurance_id');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }

    /**
     * Add/Remove insurance to/from the cart
     */
    public function updateInsuranceAction()
    {
        try {

            if(!$this->getRequest()->getParam('is_in_cart')){ // insurance to add
                if($insurance = $this->_initProduct()){ // init product insurance and check id
                    $cart = $this->_getCart();
                    $session = $this->_getSession();

                    $cart->addProduct($insurance); // qty forced in observer
                    $cart->save();

                    $session->setCartWasUpdated(true);
                    $session->addSuccess($this->__('The insurance has been added to the cart'));
                }
            }else{ // remove insurance
                if($insuranceId = $this->getRequest()->getParam('insurance_id')){ // check insurance id
                    $cart = $this->_getCart();
                    $session = $this->_getSession();
                    $items = $cart->getItems();

                    if($items && $items->getSize()){ // if cart is not empty
                        foreach ($items as $_item){
                            if($_item->getProductId() == $insuranceId){
                                $cart->removeItem($_item->getId()); // remove assurance
                            }
                        }

                        $cart->save();

                        $session->setCartWasUpdated(true);
                        Mage::helper('auguria_insurance')->setInsuranceExcluded($insuranceId, 1); // for not adding it to cart automatically
                        $session->addSuccess($this->__('The insurance has been removed from the cart'));
                    }
                }
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__("An error occurs while updating the insurance in the cart"));
            Mage::logException($e);
        }

        $this->_redirect('checkout/cart');
    }

}