<?php
/**
 * @category   Storm
 * @package    Storm_Correios
 * @copyright  Copyright (c) 2013 Willian Cordeiro de Souza
 * @author     Willian Cordeiro de Souza <williancordeirodesouza@gmail.com> 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Storm_Correios_Model_Adminhtml_Observer 
{    
    protected $_message = array(	
	'configuration' => array(
	    'severity' => Mage_AdminNotification_Model_Inbox::SEVERITY_CRITICAL,
	    'title' => 'You must configure the source address of delivery.',
	    'description' => 'The Correios module accepts deliveries only national, so the home country of delivery must be configured to Brazil. Do not forget to set the postcode too.',
	    'url' => null
	)	
    );
    
    /**
     * Check if there are errors in the module configuration
     * and requirements for the delivery method
     * work properly
     * 
     * @param Varien_Event_Observer $observer
     * @return boolean
     */
    public function checkConfiguration(Varien_Event_Observer $observer) 
    {        
        if (!Mage::getSingleton('admin/session')->isLoggedIn()) {
            return false;
        }
        
        if(!Mage::helper('correios')->isEnabled()) {
            return false;
        }
	
	if(!Mage::helper('adminnotification')->isModuleEnabled()) {
	    return false;
	}

        if (Mage::getStoreConfig('shipping/origin/country_id') != 'BR') {
	    $data = $this->_message['configuration'];
	    $data['title'] = Mage::helper('correios')->__($data['title']);
	    $data['description'] = Mage::helper('correios')->__($data['description']);
	    $data['date_added'] = date('Y-m-d H:i:s');	    
	    
	    $this->_getInbox()->getResource()->parse($this->_getInbox(), array($data));
        }
        
        return true;
    }
    
    /**
     * Gets the message model
     * 
     * @return Mage_AdminNotification_Model_Inbox
     */
    protected function _getInbox()
    {
	return Mage::getSingleton('adminnotification/inbox');
    }

}