<?php
    include 'Style.html';

    class TextFormatter {

        public $title;
        public $array;

        public function __construct($title) {
            $this->title = $title;
            $this->array = array();
        }
        public static function prettyPrint($uglyText, $description = false, $color = false) {
            if ($description) echo '<h4 color='.$color.'>'.print_r($description, true).'</h4>';
            echo '<pre color='.$color.'>'.print_r($uglyText ? $uglyText : 'false', true).'</pre>';
        }

        private static function millsToDate($timestamp) {
            if (strlen($timestamp) == 13) $timestamp = $timestamp / 1000;
            $date = date('Y-m-d H:i:s', $timestamp);
            return $date;
        }

        public static function jsonReadableDate($result) {
            $edRes = $result['result']['data'];
            for ($i = 0; $i < sizeof($edRes); $i++) {
                $readableDate = $edRes[$i]['t'];
                $edRes[$i]['t'] = $readableDate.' => '.TextFormatter::millsToDate((int) $readableDate);
            }
            $result['result']['data'] = $edRes;
            return $result;
        }

        public function addToPrint($string){
            array_push($this->array, $string);
        }

        public static function arrayToTable($array) {
            $table = '<table>';
            $count = 0;
            foreach ($array as $element) {
                if ($count == 0) {
                    $table = $table.'<thead><tr><th class="count">#</th>';
                    foreach ($element as $head) {
                        $table = $table.'<th>'.$head.'</th>';
                    }
                    $table = $table.'</tr></thead><tbody>';
                } else {
                    $table = $table.'<tr><td class="count">'.$count.'</td>';
                    foreach ($element as $e) {
                        $table = $table.'<td>'.$e.'</td>';
                    }
                    $table = $table.'</tr>';
                }
                $count = $count + 1;
            }
            $table = $table.'</tbody></table>';
            return $table;
        }

        public function collapsablePrint($array) {
            if (sizeof($array) > 0) {
                echo
                '<body>
                    <button type="button" class="collapsible">'.$this->title.'</button>
                    <div class="content">
                        '.TextFormatter::arrayToTable($array).'
                    </div>
                    <script>
                        var coll = document.getElementsByClassName("collapsible");
                        var i;
                        
                        function namedListener() {
                            this.classList.toggle("active");
                            var content = this.nextElementSibling;
                            if (content.style.display === "block") {
                                content.style.display = "none";
                            } else {
                                content.style.display = "block";
                            }
                        }

                        for (i = 0; i < coll.length; i++) {
                            coll[i].replaceWith(coll[i].cloneNode(true));
                            coll[i].addEventListener("click", namedListener);
                        }
                    </script>
                </body>';
            }
        }
    }
?>



