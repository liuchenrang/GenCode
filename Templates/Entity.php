<?php echo "<?php\r\n" ?>

<?php echo "namespace $namespace\\contract\\entity;\r\n" ?>


use sd\common\framework\BaseEntity;

/**
  <?php
    foreach ($fieldsInfo as $field) {
      echo "* @method {$field['Field']}(\$val=null);\r\n";
    }
  ?>
*/
class <?php echo $daoName;?>Entity extends BaseEntity
{
    <?php
    foreach ($fieldsInfo as $field) {

      $lineInfo = '$'.$field['Field'] ;

      $lineInfo .= ";\r\n";
      echo "/**\r\n";
      echo "*" . $field['Comment'] . "\r\n";
      echo "*/\r\n";
      echo " protected " . $lineInfo;
    }
    ?>

}

