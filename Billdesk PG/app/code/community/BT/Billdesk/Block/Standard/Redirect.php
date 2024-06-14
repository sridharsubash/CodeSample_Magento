<?php
/**************************************************************
 * 
 * @Company Name: mahiti
 * @Author: RS
 * @Date: 29 Oct 2013
 * @Description: Used for redirect on Billdesk Payment Gateway 
 *  
 ***************************************************************/
 
class BT_Billdesk_Block_Standard_Redirect extends Mage_Core_Block_Abstract
{
	
	
     protected function _toHtml()
    {
        $standard = Mage::getModel('billdesk/standard');
        $form = new Varien_Data_Form();
        $form->setAction($standard->getSubmitUrl())
            ->setId('billdesk_standard_checkout')
            ->setName('billdesk_standard_checkout')
            ->setMethod('post')
            ->setUseContainer(true);
	    $html = '<html><body>';
		$html.= $this->__('You will be redirected to the billdesk website in a few seconds.');
		$html .= '<form id ="billdesk_standard_checkout" method ="post" action ="'.$standard->getSubmitUrl().'" >';
		foreach ($standard->getBilldeskCheckoutFormFields() as $field=>$value) {
        $html.='<input type="hidden" name="msg" value="'.$value.'"/>';
		}
		$html .= '<form>';

		$html.= '<script type="text/javascript">document.getElementById("billdesk_standard_checkout").submit();</script>';
        $html.= '</body></html>';
		return $html;
    }
}
