<?php use app\Request;

echo "<?php\r\n" ?>

namespace <?php echo $namespace; ?>;
use sd\smk\common\layerobject\SimpleObject;
use app\Request;

/**
  <?php
    foreach ($fieldsInfo as $field) {
      echo " * {$field['Comment']}\r\n";
      echo " * @method {$field['Field']}(\$val=null);\r\n";
      echo " * \r\n";
      echo " * \r\n";
    }
  ?>
 */
class <?php echo $dtoName;?> extends SimpleObject
{
    <?php
    if (str_starts_with($dtoName,"Search") && str_ends_with($dtoName, "Dto")){
      echo  "public function __construct(Request \$request)\r\n";
      echo  "{ \r\n";
      echo  "self::fromObject(\$request,\$this);\r\n";
      echo  "}";
    }
    foreach ($fieldsInfo as $field) {

      $lineInfo = '$'.$field['Field'] ;

      $lineInfo .= ";\r\n";
      echo "    /**\r\n";
      echo "    *" . $field['Comment'] . "\r\n";
      echo "    */\r\n";
      echo "    protected " . $lineInfo;
    }

    ?>

    public function __construct(Request $request = null)
    {
        if ($request) {
            self::fromObject($request, $this);
        }
    }
}

