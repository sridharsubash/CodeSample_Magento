<?php
/**************************************************************
 * 
 * @Company Name: mahiti
 * @Author: RS
 * @Date: 29 Oct 2013
 * @Description: all the action which is used in interaction 
 * with billdesk payment gateway
 * 
 ***************************************************************/
 
class BT_Billdesk_IndexController extends Mage_Core_Controller_Front_Action
{
	
	 public function redirectAction()
    {
		$session = Mage::getSingleton('checkout/session');
		$session->setBilldeskStandardQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('billdesk/standard_redirect')->toHtml());
        $session->unsQuoteId();
        $session->unsRedirectUrl();
    }
    
    public function processAction(){
	
        try {
			
            $response = $this->getRequest()->getPost();                   
			$responseMsg = explode('|', $response['msg']);
			$stringWithoutCheckSum = implode("|",$responseMsg2);	
			$model = Mage::getModel('billdesk/standard');
			$checksumKey = $model->getChecksumKey();
		    
			$stringWithCheckSumValue = $stringWithoutCheckSum."|".$checksumKey;	
			$newcheckSumValue = crc32($stringWithCheckSumValue);
			
			/* End checksum value is not match */		
			
			$status = $responseMsg[14];
			
			 	
			/* start Update Response billdesk 19 July */
			
			$orderId = $responseMsg[1]; 	
			
				
			/* End Update Response billdesk */
				
			Mage::log("IPN response: ".$responseMsg, null, "billdesk.log"); 
			
			
			$model = Mage::getModel('billdesk/billdesk')->load($orderId,'order_id');
    		$model->setResponse($stringWithCheckSumValue)
			      ->setUpdateTime(now())
			      ->save();  
		      
			Mage::getModel('billdesk/ipn')->processIpnRequest($responseMsg);      
			
				
			if($status == "0300"){
				$this->_redirect('checkout/onepage/success?method=online');
			}else if($status=="0399"){
				$this->_redirect('billdesk/index/cancel');
			}else{
				$this->_redirect('billdesk/index/pending');
			}
			
        } catch (Exception $e) {
            Mage::logException($e);
        }
	}
    
    
    
   
}
