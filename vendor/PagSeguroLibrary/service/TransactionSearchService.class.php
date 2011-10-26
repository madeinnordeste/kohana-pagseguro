<?php if (!defined('PAGSEGURO_LIBRARY')) { die('No direct script access allowed'); }
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
 * Encapsulates web service calls to search for PagSeguro transactions
 */
class TransactionSearchService {
	
	const serviceName = 'transactionSearchService';
	
	private static function buildSearchUrlByCode(PagSeguroConnectionData $connectionData, $transactionCode) {
		$url   = $connectionData->getServiceUrl();
		return  "{$url}/{$transactionCode}/?".$connectionData->getCredentialsUrlQuery();
    }
	
	private static function buildSearchUrlByDate(PagSeguroConnectionData $connectionData, Array $searchParams) {
		$url = $connectionData->getServiceUrl();
		$initialDate  = $searchParams['initialDate'] != null ? $searchParams['initialDate'] : "";
		$finalDate    = $searchParams['finalDate']   != null ? $searchParams['finalDate'] 	: "";
		if ($searchParams['pageNumber'] != null) {
            $page = "&page=" . $searchParams['pageNumber'];
        }
		if ($searchParams['maxPageResults'] != null) {
            $maxPageResults = "&maxPageResults=" . $searchParams['maxPageResults'];
        }		
        return  "{$url}/?".$connectionData->getCredentialsUrlQuery()."&initialDate={$initialDate}&finalDate={$finalDate}{$page}{$maxPageResults}";
    }
    
    private static function buildSearchUrlAbandoned(PagSeguroConnectionData $connectionData, Array $searchParams) {
    	$url = $connectionData->getServiceUrl();
    	$initialDate  = $searchParams['initialDate'] != null ? $searchParams['initialDate'] : "";
    	$finalDate    = $searchParams['finalDate']   != null ? $searchParams['finalDate'] 	: "";
    	if ($searchParams['pageNumber'] != null) {
    		$page = "&page=" . $searchParams['pageNumber'];
    	}
    	if ($searchParams['maxPageResults'] != null) {
    		$maxPageResults = "&maxPageResults=" . $searchParams['maxPageResults'];
    	}
    	return  "{$url}/abandoned/?".$connectionData->getCredentialsUrlQuery()."&initialDate={$initialDate}&finalDate={$finalDate}{$page}{$maxPageResults}";
    }    
    
    
	
    /**
     * Finds a transaction with a matching transaction code
     * 
     * @param Credentials $credentials
     * @param String $transactionCode
     * @return a transaction object
     * @see transaction
     * @throws PagSeguroServiceException
     * @throws Exception
     */
    public static function searchByCode(Credentials $credentials, $transactionCode) {
		
		LogPagSeguro::info("TransactionSearchService.SearchByCode($transactionCode) - begin");
		
		$connectionData = new PagSeguroConnectionData($credentials, self::serviceName);
		
		try{
			
			$connection = new HttpConnection();
			$connection->get(
				self::buildSearchUrlByCode($connectionData, $transactionCode),
				$connectionData->getServiceTimeout(),
				$connectionData->getCharset()
			);
			$httpStatus = new HttpStatus($connection->getStatus());
			
			switch ($httpStatus->getType()) {
				
				case 'OK':
					$transaction = TransactionParser::readTransaction($connection->getResponse());
					LogPagSeguro::info("TransactionSearchService.SearchByCode(transactionCode=$transactionCode) - end ".$transaction->toString());
					break;
					
				case 'BAD_REQUEST':
					$errors = TransactionParser::readErrors($connection->getResponse());
					$e = new PagSeguroServiceException($httpStatus, $errors);
					LogPagSeguro::error("TransactionSearchService.SearchByCode(transactionCode=$transactionCode) - error ".$e->getOneLineMessage());
					throw $e;
					break;
					
				default:
					$e = new PagSeguroServiceException($httpStatus);
					LogPagSeguro::error("TransactionSearchService.SearchByCode(transactionCode=$transactionCode) - error ".$e->getOneLineMessage());
					throw $e;
					break;
					
			}
			
			return isset($transaction) ? $transaction : false;
			
		} catch (PagSeguroServiceException $e) {
			throw $e;
		} catch (Exception $e) {
			LogPagSeguro::error("Exception: ".$e->getMessage());
			throw $e;
		}
		
    }
	
    
    /**
     * Search transactions associated with this set of credentials within a date range
     * 
     * @param Credentials $credentials
     * @param String $initialDate
     * @param String $finalDate
     * @param integer $pageNumber
     * @param integer $maxPageResults
     * @return a object of TransactionSerachResult class
     * @see TransactionSearchResult
     * @throws PagSeguroServiceException
     * @throws Exception
     */
    public static function searchByDate(Credentials $credentials, $initialDate, $finalDate, $pageNumber, $maxPageResults) {
		
		LogPagSeguro::info("TransactionSearchService.SearchByDate(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - begin");
		
		$connectionData = new PagSeguroConnectionData($credentials, self::serviceName);
		
		$searchParams = Array(
			'initialDate' 	 => PagSeguroHelper::formatDate($initialDate),
			'finalDate' 	 => PagSeguroHelper::formatDate($finalDate),
			'pageNumber' 	 => $pageNumber,
			'maxPageResults' => $maxPageResults
		);
		
		try{
			
			$connection = new HttpConnection();
			$connection->get(
				self::buildSearchUrlByDate($connectionData, $searchParams),
				$connectionData->getServiceTimeout(),
				$connectionData->getCharset()
			);
			
			$httpStatus = new HttpStatus($connection->getStatus());
			
			switch ($httpStatus->getType()) {
				
				case 'OK':
					$searchResult = TransactionParser::readSearchResult($connection->getResponse());
					LogPagSeguro::info("TransactionSearchService.SearchByDate(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$searchResult->toString());
					break;
					
				case 'BAD_REQUEST':
					$errors = TransactionParser::readErrors($connection->getResponse());
					$e = new PagSeguroServiceException($httpStatus, $errors);
					LogPagSeguro::error("TransactionSearchService.SearchByDate(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$e->getOneLineMessage());
					throw $e;
					break;
					
				default:
					$e = new PagSeguroServiceException($httpStatus);
					LogPagSeguro::error("TransactionSearchService.SearchByDate(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$e->getOneLineMessage());
					throw $e;
					break;
					
			}
			
			return isset($searchResult) ? $searchResult : false;
			
		} catch (PagSeguroServiceException $e) {
			throw $e;
		} catch (Exception $e) {
			LogPagSeguro::error("Exception: ".$e->getMessage());
			throw $e;
		}
		
    }
    
    
    /**
    * Search transactions abandoned associated with this set of credentials within a date range
    *
    * @param Credentials $credentials
    * @param String $initialDate
    * @param String $finalDate
    * @param integer $pageNumber
    * @param integer $maxPageResults
    * @return a object of TransactionSerachResult class
    * @see TransactionSearchResult
    * @throws PagSeguroServiceException
    * @throws Exception
    */
    public static function searchAbandoned(Credentials $credentials, $initialDate, $finalDate, $pageNumber, $maxPageResults) {
    
    	LogPagSeguro::info("TransactionSearchService.searchAbandoned(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - begin");
    
    	$connectionData = new PagSeguroConnectionData($credentials, self::serviceName);
    
    	$searchParams = Array(
    			'initialDate' 	 => PagSeguroHelper::formatDate($initialDate),
    			'finalDate' 	 => PagSeguroHelper::formatDate($finalDate),
    			'pageNumber' 	 => $pageNumber,
    			'maxPageResults' => $maxPageResults
    	);
    
    	try{
    			
    		$connection = new HttpConnection();
    		$connection->get(
	    		self::buildSearchUrlAbandoned($connectionData, $searchParams),
	    		$connectionData->getServiceTimeout(),
	    		$connectionData->getCharset()
    		);
    			
    		$httpStatus = new HttpStatus($connection->getStatus());
    			
    		switch ($httpStatus->getType()) {
    
    			case 'OK':
    				$searchResult = TransactionParser::readSearchResult($connection->getResponse());
    				LogPagSeguro::info("TransactionSearchService.searchAbandoned(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$searchResult->toString());
    				break;
    					
    			case 'BAD_REQUEST':
    				$errors = TransactionParser::readErrors($connection->getResponse());
    				$e = new PagSeguroServiceException($httpStatus, $errors);
    				LogPagSeguro::error("TransactionSearchService.searchAbandoned(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$e->getOneLineMessage());
    				throw $e;
    				break;
    					
    			default:
    				$e = new PagSeguroServiceException($httpStatus);
    			LogPagSeguro::error("TransactionSearchService.searchAbandoned(initialDate=".PagSeguroHelper::formatDate($initialDate).", finalDate=".PagSeguroHelper::formatDate($finalDate).") - end ".$e->getOneLineMessage());
    			throw $e;
    			break;
    				
    		}
    			
    		return isset($searchResult) ? $searchResult : false;
    			
    	} catch (PagSeguroServiceException $e) {
    		throw $e;
    	} catch (Exception $e) {
    		LogPagSeguro::error("Exception: ".$e->getMessage());
    		throw $e;
    	}
    
    }    
	
}

?>