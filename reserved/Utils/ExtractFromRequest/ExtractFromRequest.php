<?php
    class ExtractFromRequest {
        public static function extractCandlesticks($request) {
            $array = array();
            foreach($request['result']['data'] as $data) {
                array_push($array, $data);
            }
            return $array;
        }

        // To be used very carefully
        // Better change directly the depth param into the request of the service
        public static function extractLastCandlesticks($request, $n) {
            $r_size = sizeof($request);
            if ($r_size < $n) return 'Error';
            for($i = $n; $i < $r_size; $i++) {
                array_shift($request);
            }
            return $request;
        }

        public static function extractCloses($request) {
            $candlestick = ExtractFromRequest::extractCandlesticks($request);
            $array = array();
            foreach($candlestick as $close) {
                array_push($array, $close['c']);
            }
            return $array;
        }
    }
?>