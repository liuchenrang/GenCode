<?php
echo "<?php\r\n";
echo "namespace  $namespace;\r\n";
?>
use sd\common\framework\exception\FrameworkException;
use sd\common\framework\SdApp;
use think\App;
use think\Service;


class <?php echo $className;?> extends Service
{
    public $app;
    public function register()
    {


        <?php foreach ($daos as $dao){ ?>
        $this->app->alias('<?php echo lcfirst($dao)?>Dao', function () use ($di){
            return new <?php echo $dao?>Dao($di);
        });
        <?php }; ?>

        <?php foreach ($services as $service){ ?>
        $this->app->alias('<?php echo lcfirst($service)?>Service', function () use ($di){
            return new <?php echo $service?>Service($di);
        });
        <?php }; ?>
        // TODO: Implement Boot() method.
    }
}
