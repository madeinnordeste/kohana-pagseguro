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

class searchTransactionsByDateInterval {

	public static function main() {
		
		$initialDate = '2011-06-10T08:50';
		$finalDate   = '2011-06-29T10:30';
		
		$pageNumber = 1;
		$maxPageResults = 20;
		
		try {
			
			/*
			* #### Crendencials ##### 
			* Substitute the parameters below with your credentials (e-mail and token)
			* You can also get your credentails from a config file. See an example:
			* $credentials = PagSeguroConfig::getAccountCredentials();
			*/			
			$credentials = new AccountCredentials("your@email.com", "your_token_here");
			$result = TransactionSearchService::searchByDate($credentials, $initialDate, $finalDate, $pageNumber, $maxPageResults);
			
			self::printResult($result, $initialDate, $finalDate);
			
		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
		
	}
	
	
	public static function printResult(TransactionSearchResult $result, $initialDate, $finalDate) {
		echo "<h2>Search transactions by date</h2>";
		echo "<h3>$initialDate to $finalDate</h3>";
		foreach($result->getTransactions() as $key => $transactionSummary) {
			echo "Code: " . 		$transactionSummary->getCode()							. "<br>";
			echo "Status: " . 		$transactionSummary->getStatus()->getTypeFromValue()	. "<br>";
			echo "Reference: " .	$transactionSummary->getReference()						. "<br>";
			echo "amount: " . 		$transactionSummary->getGrossAmount()					. "<br>";
			echo "<hr>";
		}
	}
	
	
}

searchTransactionsByDateInterval::main();

?>