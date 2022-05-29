<?php
    class ExtractFromRequest {
        private static function extractCandlesticks($request) {
            $array = array();
            $request = TextFormatter::jsonReadableDate($request);
            foreach($request['result']['data'] as $data) {
                array_push($array, $data);
            }
            return $array;
        }


        public static function candlesticksCollapsableTable($request) {
            $names   = array();
            $request = ExtractFromRequest::extractCandlesticks($request);

            $text = new TextFormatter('CANDLESTICKS');
            
            foreach($request[0] as $name => $value) {
                array_push($names, $name);
            }
            
            $text->addToPrint($names);
            
            foreach($request as $data) {
                $text->addToPrint($data, Math::isGoingUp($data) ? 'green' : 'red');
            }
            $text->collapsablePrint($text->array);
            return $text->array;
        }

        // To be used very carefully
        // Better change directly the depth param into the request of the service
        public static function extractLastCandlesticks($request, $n) {
            $candles = ExtractFromRequest::extractCandlesticks($request);
            $r_size = sizeof($candles);
            if ($r_size < $n) return 'Error';
            for($i = $n; $i < $r_size; $i++) {
                array_shift($candles);
            }
            return $candles;
        }

        public static function extractCloses($request) {
            $candlestick = ExtractFromRequest::extractCandlesticks($request);
            $array = array();
            foreach($candlestick as $close) {
                array_push($array, (['t' => $close['t'], 'c' => $close['c']]));
            }
            return $array;
        }

        public static function closesCollapsableTable($request) {
            $names   = array();
            $request = ExtractFromRequest::extractCloses($request);

            $text = new TextFormatter('CLOSES');
            
            foreach($request[0] as $name => $value) {
                array_push($names, $name);
            }
            
            $text->addToPrint($names);
            
            foreach($request as $data) {
                $text->addToPrint($data);
            }
            $text->collapsablePrint($text->array);
            return $request;
        }
    }
?>