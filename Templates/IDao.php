<?php echo "<?php\r\n" ?>
namespace <?php echo $namespace;?>\contract\dao;
use <?php echo $namespace;?>\contract\entity\<?php echo $daoName;?>Entity;;
use  sd\common\framework\contract\Pager;
use sd\common\framework\search\AdminSearchEntity;
<?php echo "use $namespace\\contract\\bo\\{$daoName}ListBo;\r\n" ?>

interface I<?php echo $daoName;?>Dao{

  public function insert(<?php echo $daoName;?>Entity $data): int;
  public function deleteById($id): bool;
  public function updateById( <?php echo $daoName;?>Entity $data): bool;
  public function selectById(int $id): ?<?php echo $daoName;?>Entity;


  /**
  * @param array $ids
  * @return array<<?php echo $daoName;?>Entity>
  */
  public function selectIdIn(array $ids): array;
  public function updateByIdIn(array $ids,<?php echo $daoName;?>Entity $data): bool;

}
