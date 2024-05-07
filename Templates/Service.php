<?php echo "<?php\r\n" ?>
namespace <?php echo $namespace;?>\impl\service;

<?php echo "use $namespace\contract\service\I{$serviceName}Service;\r\n" ?>
<?php echo "use sd\common\\framework\service\BaseService;\r\n" ?>
use <?php echo $namespace;?>\contract\entity\<?php echo $daoName;?>Entity;
<?php echo "use $namespace\impl\dao\model\\$daoName;\r\n" ?>

use sd\common\framework\search\AdminSearchEntity;
use <?php echo $namespace;?>\contract\bo\<?php echo $daoName;?>ListBo;
class <?php echo $serviceName;?>Service extends BaseService implements I<?php echo $serviceName;?>Service {

    public function save(<?php echo $daoName;?>Entity $data): int
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->insert($data);
    }
    public function upsert(<?php echo $daoName;?>Entity $data):?<?php echo $daoName;?>Entity{
        $pri = $this->upsertOnly($data);
        if(!$pri){
          return null;
        }
        return $this->getById($pri);
    }
    public function upsertOnly(<?php echo $daoName;?>Entity $data):int
    {
      $pri = $data->getAttr(<?php echo $modelName; ?>::PK);
      $entity = $pri ? $this->getById($pri) : null;
      if($entity){
           return $this->update($data) ? $pri : 0;
      }else{
           return $this->save($data);
      }
    }
    public function delete($id): bool
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->deleteById($id);
    }

    public function update(<?php echo $daoName;?>Entity $data): bool
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->updateById($data);

    }

    public function getById($id): ?<?php echo $daoName;?>Entity
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->selectById($id);
    }

    public function updateByIdIn($ids, <?php echo $daoName;?>Entity $data): bool
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->updateByIdIn($ids,$data);

    }

    function getByIds($ids): array
    {
        return $this->app->getI<?php echo $daoName;?>Dao()->selectIdIn($ids);
    }
    public function getListByAdmin(AdminSearchEntity $search): <?php echo $daoName;?>ListBo
    {
        $pager = $this->app->getI<?php echo $daoName;?>Dao()->selectAdminList($search);
        return  <?php echo $daoName;?>ListBo::pager($pager);
    }
}

