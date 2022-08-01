<script type="text/javascript" src="../../Utils/Formatter/Functions/Dropdown.js"></script>
<?php
class Dropdown {
    public static function getListElements($list) {
        $droplist = '';
        for ($i = 0; $i < sizeof($list); $i++) {
            $droplist .= '<a onclick="list(\'' . $list[$i] . '\');">' . $list[$i] . '</a>';
        }
        return $droplist;
    }
}
$droplist = Dropdown::getListElements($list);
?>
<div class="dropdown">
    <button onclick="myFunction()" class="dropbtn" id="dropd">
        <script>
        document.getElementById("dropd").innerHTML = state;
        </script>
    </button>
    <div id="myDropdown" class="dropdown-content">
        <?php echo $droplist ?>
    </div>
</div>