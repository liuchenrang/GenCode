<?php echo "<?php\r\n" ?>
<?php echo "namespace $namespace\contract\service;\r\n" ?>
use <?php echo $namespace;?>\contract\entity\<?php echo $daoName;?>Entity;
use sd\common\framework\search\AdminSearchEntity;
use <?php echo $namespace;?>\contract\bo\<?php echo $daoName;?>ListBo;
interface I<?php echo $serviceName;?>Service{

    public function save(<?php echo $daoName;?>Entity $data):int;
    public function upsert(<?php echo $daoName;?>Entity $data):?<?php echo $daoName;?>Entity;
    public function upsertOnly(<?php echo $daoName;?>Entity $data): int;
    public function delete($id): bool;
    public function update(<?php echo $daoName;?>Entity $data): bool;
    public function getById($id): ?<?php echo $daoName;?>Entity;
    public function updateByIdIn($ids,<?php echo $daoName;?>Entity $data): bool;
    /**
    * @return array<<?php echo $daoName;?>Entity>
    */
    function getByIds($ids):array;
    public function getListByAdmin(AdminSearchEntity $search): <?php echo $daoName;?>ListBo;
}
