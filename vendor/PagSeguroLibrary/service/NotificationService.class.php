<?php if (!defined('ALLOW_PAGSEGURO_CONFIG')) { die('No direct script access allowed'); }
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

/**
 * Encapsulates web service calls regarding PagSeguro notifications
 */
class NotificationService {
	
	const serviceName = 'notificationService';
	
	private static function buildTransactionNotificationUrl(PagSeguroConnectionData $connectionData, $notificationCode) {
		$url   = $connectionData->getServiceUrl();
		return "{$url}/{$notificationCode}/?".$connectionData->getCredentialsUrlQuery();
	}
	
	/**
	 * Returns a transaction from a notification code
	 * 
	 * @param Credentials $credentials
	 * @param String $notificationCode
	 * @throws PagSeguroServiceException
	 * @throws Exception
	 * @return a transaction
	 * @see Transaction
	 */
	public static function checkTransaction(Credentials $credentials, $notificationCode) {
		
		LogPagSeguro::info("NotificationService.CheckTransaction(notificationCode=$notificationCode) - begin");
		$connectionData = new PagSeguroConnectionData($credentials, self::serviceName);
		
		try {
			
			$connection = new HttpConnection();
			$connection->get(
				self::buildTransactionNotificationUrl($connectionData, $notificationCode), // URL + parâmetros de busca
				$connectionData->getServiceTimeout(), // Timeout
				$connectionData->getCharset() // charset
			);
			
			$httpStatus = new HttpStatus($connection->getStatus());
			
			switch ($httpStatus->getType()) {
				
				case 'OK':
					// parses the transaction
					$transaction = TransactionParser::readTransaction($connection->getResponse());
					LogPagSeguro::info("NotificationService.CheckTransaction(notificationCode=$notificationCode) - end ". $transaction->toString().")");
					break;
				
				case 'BAD_REQUEST':
					$errors = TransactionParser::readErrors($connection->getResponse());
					$e = new PagSeguroServiceException($httpStatus, $errors);
					LogPagSeguro::info("NotificationService.CheckTransaction(notificationCode=$notificationCode) - error ".$e->getOneLineMessage());
					throw $e;
					break;
					
				default:
					$e = new PagSeguroServiceException($httpStatus);
					LogPagSeguro::info("NotificationService.CheckTransaction(notificationCode=$notificationCode) - error ".$e->getOneLineMessage());
					throw $e;
					break;
					
			}
			
			return isset($transaction) ? $transaction : null;
			
		} catch (PagSeguroServiceException $e) {
			throw $e;
		} catch (Exception $e) {
			LogPagSeguro::error("Exception: ".$e->getMessage());
			throw $e;
		}
		
	}


}
	
?>