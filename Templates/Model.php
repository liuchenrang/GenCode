<?php echo "<?php\r\n" ?>


namespace <?php echo $namespace; ?>;


use <?php echo $namespaceModelEnum; ?>\<?php echo $tableModule; ?>Enum;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

/**
<?php
foreach ($fieldsInfo as $field) {
    echo "* {$field['Comment']};\r\n";
    echo "* @property \${$field['Field']};\r\n";
}
?>
*/
class <?php echo $modelName; ?> extends BaseModel
{
use SoftDelete;

protected $defaultSoftDelete = 0;

protected $deleteTime = 'is_del';

protected $autoWriteTimestamp = true;

protected $createTime = 'create_time';

protected $updateTime = 'update_time';

//protected $readonly = ['create_time'];

public static function tableName(): string
{
return '<?php echo $tableName; ?>';
}

public static function tablePk(): ?string
{
return '<?php echo $pk['Field']; ?>';
}
<?php
$append_text = [];
foreach ($fieldsInfo as $field) {

  if (strpos($field['Comment'], "#") > -1 && strpos($field['Comment'], "@") > -1 && strpos($field['Comment'], "=") > -1) {
    $append_text[] = "{$field['Field']}_text";
  }
}
if (count($append_text) > 0) {
  echo "public  static function getAppend(): array\r\n{\r\n";
  echo "    return    ";
  echo var_export($append_text, 1);
  echo "    ;";
  echo "\r\n}\r\n";
}

?>
<?php
//状态格式   删除状态#通过@pass=1#未通过@deny=2

foreach ($fieldsInfo as $field) {
    if (strpos($field['Comment'], "#") > -1 || strpos($field['Comment'], "@") > -1 || strpos($field['Comment'], "=") > -1) {
        $statusInfo = explode("#", $field['Comment']);
        if (count($statusInfo) >= 2) {
            echo '//' . $statusInfo[0] . "\r\n";
            array_shift($statusInfo);
          $fieldName = ucfirst(\app\common\Helper::toCamelCase($field['Field']));

          echo "public function get{$fieldName}TextAttr(\$value,\$data): string\r\n{";
          echo "    \r\n     \$status =  {$tableModule}Enum::get{$fieldName}Map();\r\n";

        }
      echo "     return \$status[\$data['{$field['Field']}']??'']??'';\r\n";
      echo "}\r\n";
    }
}
?>

}


