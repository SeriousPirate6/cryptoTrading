<?php
    class TextFormatter {
        public static function prettyPrint($uglyText) {
            echo '<pre>'.print_r($uglyText, true).'</pre><br>';
        }
    }
?>