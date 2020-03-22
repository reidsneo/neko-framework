<?php 
namespace Neko\Framework;

use Neko\Framework\App;
use Neko\Framework\Provider;
use Neko\Menu\Menu;
use Neko\Menu\Link;
use Neko\Menu\Html;
use Neko\Facade\Session;
use Neko\Framework\Http\Request;

class PluginBase extends Provider {

    function __construct() {
        global $app;
        $this->menuadmin = null;
        $this->app = $app;

        if(is_array($this->registerMenuAdmin()))
        {
            $items = $this->registerMenuAdmin();
            //var_dump($items);
            //echo "========================================================================================";
            foreach ($items as $key => $val) {
                if(array_key_exists('title',$val))
                {
                    $app->menuadmin = $app->menuadmin
                    ->addIf($this->app->request->can("acc",$val['url']),Link::to($val['url'], '<i class="icon-'.$val['icon'].'"></i>'.' <span>'.$val['title'].'</span>')->addClass('nav-link'));
                    //$this->app->debug["messages"]->addMessage($val['url']."  ".$this->app->request->can("acc",$val['url']));
                }else{
                    self::recursivemenu($app->menuadmin,$key,$val);
                }
            }
        }

        //$app->debug["messages"]->addMessage(Session::get("user")['access']);
        //$app->debug["messages"]->addMessage(self::isacc());//$app->router->getRoutes());
    }

    function recursivemenu($menu,$key,$array)
    {
        if(is_array($array) && count($array)>0 && !array_key_exists('title',$array) && $key!="icon")
        {
            $subitem = $array;
            unset($subitem['icon']);
            
            $this->menuadmin = $menu
                ->submenuif(
                    $this->app->request->countsub($subitem),
                    Link::to('#', '<i class="icon-'.$array['icon'].'"></i>'.' <span>'.$key.'</span>')
                    ->addClass('nav-link'), function (Menu $menu) use ($array,$key,$subitem) {
                        $this->app->debug["messages"]->addMessage($key ." ".$this->app->request->countcan("acc",$subitem));
                        $menu
                            ->addParentClass('nav-item-submenu')
                            ->addItemParentClass('nav-item')
                            ->addClass('nav nav-group-sub')
                            ->setActiveClass('nav-item-expanded nav-item-open')
                            ->setAttribute('data-submenu-title',$key);
                        
                        foreach ($array as $k => $v) {
                            if(is_array($v) && array_key_exists('title',$v))
                            {
                             $menu
                                ->addIf($this->app->request->can("acc",$v['url']),Link::to($v['url'], '<i class="icon-'.$v['icon'].'"></i>'.' <span>'.$v['title'].'</span>')
                                ->addClass('nav-link'));
                                //$this->app->debug["messages"]->addMessage($v['url']."  ".$this->app->request->can("acc",$v['url']));
                            }else{
                                self::recursivemenu($menu,$k,$v);
                            }
                        }
                    }
                );
        }
    }

    public function register()
	{

    }
    
	public function boot()
	{
        echo "aye";
    }

    public function registerComponents()
    {
        return [];
    }
    
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
    //public function doregister()
    //{
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
        
    //}
}
