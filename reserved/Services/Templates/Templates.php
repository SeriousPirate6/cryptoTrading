<?php
    include '../../Utils/RunQuery/RunQuery.php';
    include '../../Services/Requests/Requests.php';
    include '../../Services/Methods/MethodsImpl.php';

    class Templates {
        // Returns candlesticks for a list of currencies and save the results on DB;
        public static function candlestick() {
            $currList           = CurrenciesList::getOptions();
            $multiMethods       = new GetMultipleMethods();
            $multiMethodsImpl   = $multiMethods->getCandlestick($currList, m1, d1);

            $requests           = SendRequest::sendMultiRequest($multiMethodsImpl);

            $queries            = MultipleInsertTable::currencyValue($requests);
            RunQuery::multipleInsert($queries);
        }

        // Return all instrument names available and save them on DB;
        public static function currencyData() {
            
            // Create table if non existing;
            $method             = new GetMethods;
            $createCurrData     = CreateTable::currencyData();
            RunQuery::create($createCurrData);

            // API call;
            $methodImpl         = $method->getInstruments();
            $request            = SendRequest::sendReuquest($methodImpl);

            // Create query and save on DB;
            $queries            = MultipleInsertTable::currencyData($request);
            RunQuery::multipleInsert($queries);
        }
    }
?>