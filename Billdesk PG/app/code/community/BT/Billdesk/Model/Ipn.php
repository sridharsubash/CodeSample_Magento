<?php
/**************************************************************
 * 
 * @Company Name: MAHITI
 * @Author: RS
 * @Date: 29 Oct 2013
 * @Description: Used for update order status according 
 * to API response
 * 
 ***************************************************************/

class BT_Billdesk_Model_Ipn
{
  
    protected $_order = null;

  
    protected $_request = array();

    
    public function processIpnRequest(array $request)
    {	
		$this->_request = $request;
    	try {
				 $this->_getOrder();
				 $this->_processOrder();
		          
        } catch (Exception $e) {
          
        }
      
    }

	
   
   
    protected function _getOrder()
    {
        if (empty($this->_order)) {
           
			$id = $this->_request['1'];
	
			$this->_order = Mage::getModel('sales/order')->loadByIncrementId($id);
			
    		if (!$this->_order->getId()) {
                throw new Exception(sprintf('Wrong order ID: "%s".', $id));
            }
         
        }
        return $this->_order;
    }


   protected function _processOrder()
    {
		
        $this->_order = null;
        $this->_getOrder();
		try {
			
				
		if($this->_request['14'] =='0300'){
				$this->_request['payment_status'] = 'Completed';
			}else if($this->_request['14'] =='0399'){
				$this->_request['payment_status'] = 'Failed';
			}else if($this->_request['14'] =='0001'){
				$this->_request['payment_status'] = 'Voided';
			}else if($this->_request['14'] =='0002'){
				$this->_request['payment_status'] = 'Voided';
			}else{
				$this->_request['payment_status'] = 'Voided';
			}
			

			
		 switch ($this->_request['payment_status']) {
         
             case 'Completed':
				 $this->_registerPaymentCapture();
                 break;            
             case 'Failed' :
                 $this->_registerPaymentFailure();
                 break;
             case 'Voided' :
                 $this->_registerPaymentVoid();
                 break;
             default:
                    throw new Exception("Cannot handle payment status '{$paymentStatus}'.");
            }
        } catch (Mage_Core_Exception $e) {
           
        }
    }

   
    
    /**
     * Process completed payment 
     */
    protected function _registerPaymentCapture()
    {
 	   $payment = $this->_order->getPayment(); 
       $this->_order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, 'complete','','')->save();
       $this->_order->sendNewOrderEmail();
    }


    /**
     * Treat failed payment as order cancellation
     */
    protected function _registerPaymentFailure()
    {
		$order =  $this->_getOrder();
		   if ($order->getId()) {
                $order->cancel()->save();
            }
 	  $this->_order->setState(Mage_Sales_Model_Order::STATE_CANCELED, 'canceled','','')->save();
   
    }


    /**
     * Process voided authorization
     */
    protected function _registerPaymentVoid()
    {
	      
	      $order =  $this->_getOrder();
		   if ($order->getId()) {
               // $order->cancel()->save();
            }
       $this->_order->setState(Mage_Sales_Model_Order::STATE_CANCELED, 'canceled','','')->save();
    }

   
}
