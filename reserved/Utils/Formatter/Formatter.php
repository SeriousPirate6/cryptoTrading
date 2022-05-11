<?php
    include 'Style.html';

    class TextFormatter {

        public $title;
        public $array;

        public function __construct($title) {
            $this->title = $title;
            $this->array = array();
        }
        public static function prettyPrint($uglyText) {
            echo '<pre>'.print_r($uglyText, true).'</pre>';
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



