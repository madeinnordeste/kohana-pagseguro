<?php
/*
************************************************************************
Copyright [2011] [PagSeguro Internet Ltda.]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
************************************************************************
*/

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";

/**
 * Class with a main method to illustrate the usage of the domain class PaymentRequest
 */
class createPaymentRequest {
	
	public static function main () {
		
		// Instantiate a new payment request
		$paymentRequest = new PaymentRequest();
		
		// Sets the currency
		$paymentRequest->setCurrency("BRL");
		
		// Add an item for this payment request
		$paymentRequest->addItem('0001', 'Notebook prata', 2,430.00);
		
		// Add another item for this payment request
		$paymentRequest->addItem('0002', 'Notebook rosa',  2,560.00);
		
		// Sets a reference code for this payment request, it is useful to identify this payment in future notifications.
		$paymentRequest->setReference("REF1234");
		
		// Sets shipping information for this payment request
		$CODIGO_SEDEX = ShippingType::getCodeByType('SEDEX');
		$paymentRequest->setShippingType($CODIGO_SEDEX);
		$paymentRequest->setShippingAddress('01452002',  'Av. Brig. Faria Lima',  '1384', 'apto. 114', 'Jardim Paulistano', 'São Paulo', 'SP', 'BRA');
		
		// Sets your customer information.
		$paymentRequest->setSender('João Comprador', 'comprador@uol.com.br', '11', '56273440');
		
		$paymentRequest->setRedirectUrl("http://www.lojamodelo.com.br");
		
		try {
			
			/*
			* #### Crendencials ##### 
			* Substitute the parameters below with your credentials (e-mail and token)
			* You can also get your credentails from a config file. See an example:
			* $credentials = PagSeguroConfig::getAccountCredentials();
			*/			
			$credentials = new AccountCredentials("your@email.com", "your_token_here");
			
			// Register this payment request in PagSeguro, to obtain the payment URL for redirect your customer.
			$url = $paymentRequest->register($credentials);
			
			self::printPaymentUrl($url);
			
		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
		
	}
	
	public static function printPaymentUrl($url) {
		if ($url) {
			echo "<h2>Criando requisição de pagamento</h2>";
			echo "<p>URL do pagamento: <strong>$url</strong></p>";
			echo "<p><a title=\"URL do pagamento\" href=\"$url\">Ir para URL do pagamento.</a></p>";
		}
	}
	
}

createPaymentRequest::main();

?>
