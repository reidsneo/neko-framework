<?php 
namespace Neko\Framework;

use Neko\Framework\App;
use Neko\Framework\Controller;

abstract class PluginBase extends Controller {

/*
        //echo "constructed<br>";
        //echo get_class($this);
        //var_dump($app);
        //parent::__construct($app);
        //var_dump($app->router);
        //var_dump(get_declared_classes());
        if (class_exists(get_class($this))) {
            echo "<br>exists<br>";
            //$this->boot($app);
        }else{
            
        }
        //self::doregister();
        //echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
        //echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
        //$this->boot();
       // $this->doregister();
       // self::register();
    }
*/
    public function doregister()
    {
        //echo "<br>register pluginbase";
        
        /*
        var_dump($this->registerComponents());
        echo "<br>";*/
        //$example = $this->registerComponents();
        //foreach ($example as $key => $val) {
        //    $this->providers[$val] = new $key($this);
        //}
                
        //$this->providers[hellobase::class] =   new \Hero\Hello\Controller\hellobase($this);
        //$class_methods = get_class_methods('Hero\Hello\Controller\hellobase');
        //var_dump($class_methods);
      // $this->register(hellobase::class,new \Hero\Hello\Controller\hellobase($app));
  //  $this[hellobase::class] = new \Hero\Hello\Controller\hellobase($this);
    //$cara2 = $this[hellobase::class];
       // $this->hellobase = new \Hero\Hello\Controller\hellobase($this);

        //$this->bind(hellobase::class,new \Hero\Hello\Controller\hellobase($this));
        
    }
}
