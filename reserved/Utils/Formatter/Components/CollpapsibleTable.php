<?php
class CollapsibleTable {
    public $title;
    public $array;
    public $colors;

    public function __construct($title) {
        $this->title    = $title;
        $this->array    = array();
        $this->colors   = array();
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

    public function collapsablePrint($array, $open = false) {
        if (sizeof($array) > 0) {
            $collapsible = '';
            $display = '';
            if ($open) {
                $collapsible = 'active';
                $display = 'style="display: block;"';
            }
            echo
            '<body>
                <button type="button" class="collapsible ' . $collapsible . '"><h4>' . $this->title . '</h4></button>
                <div class="content"' . $display . '>
                    ' . CollapsibleTable::arrayToTable($array, $this->colors) . '
                </div>
                <script type="text/javascript" src="../../Utils/Formatter/Functions/CollapsibleTable.js"></script>
            </body>';
        }
    }
}