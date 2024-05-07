<?php  echo "<?php\r\n"; ?>
declare (strict_types=1);

<?php echo "namespace $namespace;\r\n" ?>

use sd\common\framework\ContainerAlias;
use sd\common\framework\exception\FrameworkException;
use sd\common\framework\SdApp;
use think\App;
use think\Service;

/**
 * 应用服务类
 */
class AppProviderService extends Service
{
  public  $app;

  public function register()
  {
    if (!$this->app instanceof SdApp){
      throw new FrameworkException();
    }
    //$this->app->alias(IUserService::class, function () {
    //  return new UserService($this->app);
    //});

  }

  public function boot()
  {
    // 服务启动
  }
}
