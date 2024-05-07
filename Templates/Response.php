<?php echo "<?php\r\n" ?>

namespace <?php echo $namespace; ?>;
use sd\smk\common\layerobject\SimpleObject;


class <?php echo $daoName; ?>Rsp  extends SimpleObject
{
<?php
foreach ($fieldsInfo as $field) {
    if ($field['Field'] == "is_del") continue;
    $lineInfo = '$' . $field['Field'];

    $lineInfo .= ";\r\n";
    echo "    /**\r\n";
    echo "    *" . $field['Comment'] . "\r\n";
    echo "    */\r\n";
    echo "    protected " . $lineInfo;


}
?>
<?php
foreach ($fieldsInfo as $field) {
    if ($field['Field'] == "is_del") continue;
    $lineInfo = '$' . $field['Field'];
    $lineInfo .= ";\r\n";
    echo "    /**\r\n";
    echo "    *" . $field['Comment'] . "\r\n";
    echo "    */\r\n";
    echo "    protected " . $lineInfo;
    echo "\r\n";
    echo " /**\r\n";
    echo "  *" . $field['Comment'] . "\r\n";
    echo "  */\r\n";
    echo " function {$field['Field']}(\$val=null){\r\n";
    echo "    if(\$val !== null){\r\n";
    echo "       \$this->{$field['Field']} = \$val;\r\n";
    echo "    }else{\r\n";
    echo "       return \$this->{$field['Field']};\r\n";
    echo "    }\r\n";
    echo " }\r\n";
}
?>

}

