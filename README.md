Este módulo ultiliza as Bibliotecas distribuidas pelo pagseguro (http://pagseguro.uol.com.br)

# DOCUMENTAÇÃO

https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html#pagamentos


# EXEMPLOS DE USO

    ## Requisicao de Pagamento
    
    require Kohana::find_file('vendor', 'PagSeguroLibrary/PagSeguroLibrary');
    
    $paymentRequest = new PaymentRequest();  

    $paymentRequest->addItem('0001', 'Notebook', 1, 2430.60); 
    $paymentRequest->setCurrency("BRL");
    $paymentRequest->setShippingType(3);        //sem envio   
    $paymentRequest->setReference("1-458932");  //referencia da transacao

    $credentials = PagSeguroConfig::getAccountCredentials();  

    $url = $paymentRequest->register($credentials);  




## Recebimento de notificação
    
    require Kohana::find_file('vendor', 'PagSeguroLibrary/PagSeguroLibrary');
    
    $credentials = PagSeguroConfig::getAccountCredentials();  
    
    //Tipo de notificação recebida
    $type = $_POST['notificationType'];  

    //Código da notificação recebida
    $code = $_POST['notificationCode'];  

    //Verificando tipo de notificação recebida 
    if ($type === 'transaction') {  

        //Obtendo o objeto Transaction a partir do código de notificação
        $transaction = NotificationService::checkTransaction(  
            $credentials,  
            $code // código de notificação  
            );  
    
            //codigo da transacao no pagseguro
            $tcode = $transaction->getCode();
    
            //codigo de referencia no nosso sistema
            $reference = $transaction->getReference();
    
            $status = $transaction->getStatus()->getValue();
            //1 	Aguardando pagamento
            //2 	Em análise
            //3 	Paga
            //4 	Disponível
            //5 	Em disputa
            //6 	Devolvida
            //7 	Cancelada
    
    
    }




## Pesquisa por Código da Transação
    
    require Kohana::find_file('vendor', 'PagSeguroLibrary/PagSeguroLibrary');
   
    //Definindo as credenciais
    $credentials = PagSeguroConfig::getAccountCredentials();  

    //Código identificador da transação/
    $transaction_id = '59A13D84-52DA-4AB8-B365-1E7D893052B0';  

    //Realizando uma consulta de transação a partir do código identificador  
    //para obter o objeto Transaction 

    $transaction = TransactionSearchService::searchByCode(  
        $credentials,  
        $transaction_id  
    );




## Pesquisa de Transaçãoes por Intervalo de Datas
    
    require Kohana::find_file('vendor', 'PagSeguroLibrary/PagSeguroLibrary');
    
    // Definindo as credenciais  
    $credentials = PagSeguroConfig::getAccountCredentials();  

    // Definindo a data de ínicio da consulta 
    $initialDate = '2011-06-01T08:50';  

    //Definindo a data de término da consulta 
    $finalDate   = '2011-06-29T10:30';  

    //Definindo o número máximo de resultados por página
    $maxPageResults = 20;  

    //Definindo o número da página
    $pageNumber = 1;  

    //Realizando a consulta 
    $result = TransactionSearchService::searchByDate(  
        $credentials,       // credenciais  
        $initialDate,       // data de ínicio  
        $finalDate,         // data de término  
        $pageNumber,        // número da página  
        $maxPageResults     // número máximo de resultados por página  
        );  

        //Obtendo as transações do objeto TransactionSearchResult
        $transactions = $result->getTransactions();





## Tratamento de Exceções
    
    require Kohana::find_file('vendor', 'PagSeguroLibrary/PagSeguroLibrary');

    //Informando as credenciais (omitindo token)
    $credentials = new AccountCredentials("suporte@lojamodelo.com.br", " ");   

    try {  

        //Fazendo uma requisição via API com credenciais incorretas
        $url = $paymentRequest->register($credentials);  

    } catch (PagSeguroServiceException $e) {  

        echo $e->getHttpStatus(); // imprime o código HTTP  

        foreach ($e->getErrors() as $key => $error) {  
            echo $error->getCode(); // imprime o código do erro  
            echo $error->getMessage(); // imprime a mensagem do erro  
        }  

    }




