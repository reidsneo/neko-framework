<?php 

namespace Neko\Framework;

use Closure;
use Neko\Framework\App;
use Neko\Framework\MacroableTrait;

class Theme {

    use MacroableTrait;

    public function asset_a($assetpath)
    {
        global $app;
        return "/theme/a_".$app->config['admin_theme']."/asset/".$assetpath;
    }

    public function asset($assetpath)
    {
        global $app;
        return "/theme/".$app->config['user_theme']."/asset/".$assetpath;
    }

}
