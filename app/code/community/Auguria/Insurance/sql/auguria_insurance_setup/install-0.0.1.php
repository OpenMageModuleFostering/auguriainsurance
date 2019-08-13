<?php
/**
 * @category   Auguria
 * @package    Auguria_ProductMatrix
 * @author     Auguria
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 */

/* @var $this Mage_Catalog_Model_Resource_Setup */

$entityTypeId = $this->getEntityTypeId('catalog_product');

# add price attribute for insurance product

/* @var $attribute array */
$attribute = $this->getAttribute($entityTypeId, 'price');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'price', 'apply_to', $applyTo); // update attribute

# add tax class id attribute for insurance product

/* @var $attribute Mage_Catalog_Model_Entity_Attribute */
$attribute = $this->getAttribute($entityTypeId, 'tax_class_id');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'tax_class_id', 'apply_to', $applyTo); // update attribute

# add group price id attribute for insurance product

/* @var $attribute Mage_Catalog_Model_Entity_Attribute */
$attribute = $this->getAttribute($entityTypeId, 'group_price');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'group_price', 'apply_to', $applyTo); // update attribute

# add special price id attribute for insurance product

/* @var $attribute Mage_Catalog_Model_Entity_Attribute */
$attribute = $this->getAttribute($entityTypeId, 'special_price');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'special_price', 'apply_to', $applyTo); // update attribute

# add special from date

$attribute = $this->getAttribute($entityTypeId, 'special_from_date');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'special_from_date', 'apply_to', $applyTo); // update attribute

# add special to date

$attribute = $this->getAttribute($entityTypeId, 'special_to_date');

$applyTo = $attribute['apply_to']; // get existing apply to product type for attribute
$applyToAsArray = explode(',', $applyTo);

$applyToAsArray[] = Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID; // add insurance produc type
$applyToAsArray = array_unique($applyToAsArray); // prevent doublon
$applyTo = join(',', $applyToAsArray);

$this->updateAttribute($entityTypeId, 'special_to_date', 'apply_to', $applyTo); // update attribute

# add insurance group for each existing attribute set

$groupLabel = 'Insurance';

$attributeSets = $this->_conn->fetchAll('select * from '.$this->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
foreach ($attributeSets as $attributeSet) {
    $setId = $attributeSet['attribute_set_id'];
    $this->addAttributeGroup($entityTypeId, $setId, $groupLabel);
}

# add auguria_insurance_is_default attribute for insurance product

// only for insurance products
$productTypes = array(
        Auguria_Insurance_Model_Catalog_Product_Type_Insurance::AUGURIA_INSURANCE_PRODUCT_TYPE_ID
);
$productTypes = join(',', $productTypes);

// auguria_insurance_is_default : define if the insurance is added by default in cart
$this->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'auguria_insurance_is_default', array(
        'group'             => $groupLabel,
        'backend'           => 'catalog/product_attribute_backend_boolean',
        'frontend'          => '',
        'label'             => 'Insurance is added by default in cart',
        'input'             => 'select',
        'source'            => 'eav/entity_attribute_source_boolean',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '',
        'apply_to'          => $productTypes,
        'visible_on_front'  => false,
        'used_in_product_listing' => true
));