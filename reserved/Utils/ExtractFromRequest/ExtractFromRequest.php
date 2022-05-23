<?php
    class ExtractFromRequest {
        public static function extractCandlesticks($request) {
            $array = array();
            foreach($request['result']['data'] as $data) {
                array_push($array, $data);
            }
            return $array;
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