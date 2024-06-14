<?php

class BT_Billdesk_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
	
   protected $_code = 'billdesk';
 
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = false;
	protected $_canUseForMultishipping  = false;
 
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('billdesk/standard_form', $name)
            ->setMethod('billdesk')
            ->setPayment($this->getPayment())
            ->setTemplate('billdesk/standard/form.phtml');

        return $block;
    }
	
	
	 /**
     * Return Order place redirect url
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
          return Mage::getUrl('billdesk/index/redirect', array('_secure' => true));
    }

			
	
	 /**
     * Return form field array
     *
     * @return array
     */
	
	

	
    public function getBilldeskCheckoutFormFields()
    {
        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
		
			
		$Additionalinfo6 = "NA";		
		$baseTotal = $order->getGrandTotal();
		$customerId = $order->getCustomerId();
		if($customerId==""){
		$customerId = '0';
		}
		$email = $order->getCustomerEmail();
		
		/* Start Replace special char from mobile no 06 November */
		
		//$customer = Mage::getSingleton('customer/session')->getCustomer();
		//$mobile = $customer->getMobile();
		$mobile = $order->getShippingAddress()->getTelephone();
		$charsArray = array('!','@','#','$','%','^','&','*','+','-');
		$mobile = str_replace($charsArray,'',$mobile);
		if($mobile=="" || !is_numeric($mobile)){
			$mobile = '0';
		}
		
		/* End Replace special char from mobile no 06 November */		
		
		
					
		$transactionId = $orderIncrementId ;	

        $data = array(
					'MerchantID' => $this->getMerchantId(),
					'CustomerID' => $orderIncrementId,
					'NA' => 'NA',
					'TxnAmount' => round($baseTotal,2),
					'NA1' => 'NA',
					'NA2' => 'NA',
					'NA3' => 'NA',
					'CurrencyType' => 'INR',
					'NA4' => 'NA',
					'TypeField1' => 'R',
					'SecurityID' =>$this->getSecurityId(),
					'NA5' => 'NA',
					'NA6' => 'NA',
					'TypeField2' =>'F',
					'Additionalinfo1' => $customerId,
					'Additionalinfo2' => $email,
					'Additionalinfo3' => $mobile,
					'Additionalinfo4' => $transactionId,
					'NA7'=>'NA',
					//'Additionalinfo6'=>$Additionalinfo6,
					'NA8'=>'NA',
					'NA9'=>'NA',
					'RU' => $this->getProcessUrl()
					//'checksum' => $this->getChecksumKey()
				);
				
										
		     $newData = implode("|", $data);

			Mage::log("Without crc: ".$newData, null, "billdesk.log"); 
			 
			 /* This is most important part ONS(16 May 2012) */
			 
			$newDataWithChecksumKey = $newData."|".$this->getChecksumKey();
			 
			$checksum = crc32($newDataWithChecksumKey);
			
			$dataWithCheckSumValue = $newData."|".$checksum;
		
			 
			 /* End This is most important part */
			
			Mage::log("With crc: ".$dataWithCheckSumValue, null, "billdesk.log"); 


			$newDataArray = array('msg' => $dataWithCheckSumValue);
		
		
		
			$api = Mage::getModel('billdesk/api_standard');
		
			/* start insert billdesk 19 July */
		
			$this->saveRequest($orderIncrementId,$dataWithCheckSumValue);
			$this->createLogFile($data);
		
			/* start insert Response billdesk 19 July */
		
		
		
		//$api->createLogFile($data);
		return $newDataArray;
		
        //return $result;
    }
	
	  
	  public function getProcessUrl(){
	   $processUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'billdesk/index/process';
	   return $processUrl;
	   }			
		
	
		public function  getSubmitUrl(){			 
			  $submitUrl = Mage::getStoreConfig('payment/billdesk/submit_url');
			  return $submitUrl;
		 
		  }
		  
		public function getMerchantId(){
			  
			  $merchantId = Mage::getStoreConfig('payment/billdesk/merchant_id');
			  return $merchantId ;
			
		}  
		
		
		public function getSecurityId(){
			  $securityId = Mage::getStoreConfig('payment/billdesk/security_id');
			  return $securityId;
		}
		
		
		public function getChecksumKey(){
			  
			  $checksumKey = Mage::getStoreConfig('payment/billdesk/checksum_key');
			  return $checksumKey;
			
		}  
		
		
				 
		 public function getCheckout() 
		{
			return Mage::getSingleton('checkout/session');
		}
		
		
		public function saveRequest($orderId,$request){
			
			$model = Mage::getModel('billdesk/billdesk');
			$model->setOrderId($orderId)
			      ->setRequest($request)
			      ->setCreatedTime(now());
			      $model->save();  
		}
		
		 public function createLogFile($request)
	{
        $this->_debug(array('request' => $request)); 
        return $request;
    }
    
     protected function _debug($request)
    {
            $file = "Billdesk_response.log";
            Mage::getModel('core/log_adapter', $file)->log($request);
       
    }

		
		
    
    
			
}
