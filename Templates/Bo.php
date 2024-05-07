<?php echo "<?php\r\n" ?>

namespace <?php echo $namespace; ?>;
use sd\smk\common\layerobject\SimpleObject;

/**
  <?php
    foreach ($fieldsInfo as $field) {
      echo " *" . $field['Comment'] . "\r\n";
      echo " * @method {$field['Field']}(\$val=null);\r\n";
    }
  ?>
 */
class <?php echo $daoName;?>Bo extends SimpleObject
{
    <?php
    foreach ($fieldsInfo as $field) {

      $lineInfo = '$'.$field['Field'] ;

      $lineInfo .= ";\r\n";
      echo "    protected " . $lineInfo;
    }
    ?>

}

