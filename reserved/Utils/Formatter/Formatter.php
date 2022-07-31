<?php
include 'Style.html';

class TextFormatter {

    public $title;
    public $array;
    public $colors;

    public function __construct($title) {
        $this->title    = $title;
        $this->array    = array();
        $this->colors   = array();
    }
    public static function prettyPrint($uglyText, $description = false, $color = false) {
        if ($description) echo '<h4 color=' . $color . '>' . print_r($description, true) . '</h4>';
        echo '<pre color=' . $color . '>' . print_r($uglyText ? $uglyText : 'false', true) . '</pre>';
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
            $edRes[$i]['t'] = $readableDate . ' => ' . TextFormatter::millsToDate((int) $readableDate);
        }
        $result['result']['data'] = $edRes;
        return $result;
    }

    public function addToPrint($string, $color = false) {
        array_push($this->array, $string);
        array_push($this->colors, $color);
    }

    public static function arrayToTable($array, $colors = false) {
        $table = '<table>';
        $count = 0;
        foreach ($array as $element) {
            if ($count == 0) {
                $table = $table . '<thead><tr><th class="count">#</th>';
                foreach ($element as $head) {
                    $table = $table . '<th>' . $head . '</th>';
                }
                $table = $table . '</tr></thead><tbody>';
            } else {
                $table = $table . '<tr style="background: ' . $colors[$count] . '"><td class="count">' . $count . '</td>';
                foreach ($element as $e) {
                    $table = $table . '<td>' . $e . '</td>';
                }
                $table = $table . '</tr>';
            }
            $count = $count + 1;
        }
        $table = $table . '</tbody></table>';
        return $table;
    }

    public function collapsablePrint($array) {
        if (sizeof($array) > 0) {
            echo
            '<body>
                    <button type="button" class="collapsible"><h4>' . $this->title . '</h4></button>
                    <div class="content">
                        ' . TextFormatter::arrayToTable($array, $this->colors) . '
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

    public static function switchButton($mills) {
        return "
            <h1 id=\"demo\"></h1>

            <label class='switch'>
                <input type='checkbox' id='test'>
                <span class='slider round'></span>
            </label>

            <script>
                var bool = false;
                
                function App() {}

                App.prototype.setState = function(state) {
                    localStorage.setItem('checked', state);
                }

                App.prototype.getState = function() {
                    return localStorage.getItem('checked');
                }

                function init() {
                    var app = new App();
                    var state = app.getState();
                    var checkbox = document.querySelector('#test');

                    if (state == 'true') {
                        checkbox.checked = true;
                        document.getElementById(\"demo\").innerHTML = \"DYNAMIC\";
                        setInterval(foo, " . ($mills * 1000) . ");
                    } else {
                        document.getElementById(\"demo\").innerHTML = \"STATIC\";
                    }

                    checkbox.addEventListener('click', function() {
                        app.setState(checkbox.checked);
                    });
                }

                init();

                function foo () {
                    if (bool) return;
                    console.log(\"RUNNING\");
                    location.reload();
                }

                document.addEventListener('DOMContentLoaded', function () {
                    var checkbox = document.querySelector('input[type=\"checkbox\"]');

                    checkbox.addEventListener('change', function () {
                        if (checkbox.checked) {
                            document.getElementById(\"demo\").innerHTML = \"DYNAMIC\";
                            location.reload();
                            console.log('Checked');
                        } else {
                        document.getElementById(\"demo\").innerHTML = \"STATIC\";
                            bool = true;
                            console.log(bool);
                            console.log('Not checked');
                        }
                    });
                });
            </script>";
    }

    private static function getListElements($list) {
        $droplist = '';
        for ($i = 0; $i < sizeof($list); $i++) {
            $droplist .= '<a href="#" onclick="list(\'' . $list[$i] . '\');">' . $list[$i] . '</a>';
        }
        echo '<script>
                function App() {}
                App.prototype.setState = function(state) {
                    localStorage.setItem(\'listItem\', state);
                }

                App.prototype.getState = function() {
                    return localStorage.getItem(\'listItem\');
                }

                var app = new App();
                var state = app.getState();

                function list(element) {
                    app.setState(element);
                    document.getElementById("dropd").innerHTML = element;
                    location.reload();
                }
            </script>';
        return $droplist;
    }

    public static function dropdown($list) {
        return '<div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn" id="dropd">
                        <script>
                            document.getElementById("dropd").innerHTML = state;
                        </script>
                    </button>
                    <div id="myDropdown" class="dropdown-content">
                        ' . TextFormatter::getListElements($list) . '
                    </div>
                    </div>

                    <script>
                        function myFunction() {
                            document.getElementById("myDropdown").classList.toggle("show");
                        }

                        window.onclick = function(event) {
                            if (!event.target.matches(\'.dropbtn\')) {
                                var dropdowns = document.getElementsByClassName("dropdown-content");
                                var i;
                                for (i = 0; i < dropdowns.length; i++) {
                                    var openDropdown = dropdowns[i];
                                    if (openDropdown.classList.contains(\'show\')) {
                                        openDropdown.classList.remove(\'show\');
                                    }
                                }
                            }
                        }
                        $(document).ready(function () { 
                            createCookie("gfg", state, "10"); 
                        }); 

                        // Function to create the cookie 
                        function createCookie(name, value, days) { 
                            var expires; 
                            
                            if (days) { 
                                var date = new Date(); 
                                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
                                expires = "; expires=" + date.toGMTString(); 
                            } 
                            else { 
                                expires = ""; 
                            } 
                            
                            document.cookie = escape(name) + "=" + 
                                escape(value) + expires + "; path=/"; 
                        }
                    </script>';
    }
}