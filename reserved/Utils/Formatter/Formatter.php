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

        public function collapsablePrint() {
            echo
            '<body>
                <button type="button" class="collapsible">'.$this->title.'</button>
                <div class="content">
                    <p><pre>'.print_r($this->array, true).'</pre></p>
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
?>



