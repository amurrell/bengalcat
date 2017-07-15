<div class="full-width-hero">
    <div class="hero-text">
        [:hero text]
    </div>
</div>
<div class="container">

    <h1>How did we get here? Routing!</h1>
    
    Congratulations, you've altered the route <code>/</code> to use the <strong>Home</strong> controller instead of the <strong>Install</strong> controller.
    
    
    
    <h2>
        Let's render! Break-it-down.
    </h2>
    
    
<pre data-plugin="codebox" data-title="app/controllers/example/view/Home.php">namespace Bc\App\Controllers\Example\View;

use Bc\App\RouteExtenders\ExampleRouteExtender;

class Home extends ExampleRouteExtender {
    
    protected function init()
    {
        $this->render(
            $this->getThemePart('/src/main/home.php'),
            null, 
            [
                '&lbrack;:nav&rbrack;' => $this->nav->getNav(true),
                '&lbrack;:hero text&rbrack;' => $this->getThemePartContents('/src/tokenHTML/hero/text-home.php')
            ]
        );
    }
}</pre>
    
    <ul>
        <li><code>init()</code> is a required method of route controllers. You must have it!</li>
        <li>The job of the <code>init()</code> is to call render, or <a href="/docs/controllers/#init-json">you could echo out json and exit!</a></li>
        <li>Call <code>render()</code> &HorizontalLine; it takes 3 arguments: the path to the template file, the data (optional), and the tokens to apply (optional).
        
            <br/>
            <br/>
            <table class="table table-bordered">
                <tr>
                    <td>
                        <strong>path</strong> 
                    </td>
                    <td>
                        this is dependent on which theme you are using. If you want to <a href="docs/themes/#default">use the default theme</a>, defined in settings, then you can use <code>$this->getThemePart()</code> which will append what you pass to it to <code>html/themes/yourtheme</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>data</strong> 
                    </td>
                    <td>
                        this is an object or array of data that you want to pass to your template. Access it as <code>$data</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><a href="/docs/tokens/">tokens</a></strong></strong> 
                    </td>
                    <td>
                        this is an associative array of token => value format, where the token name (ie '&lbrack;:hero text&rbrack;') can be used in plain HTML in the template file and replaced when rendered with either nothing (no token value supplied) or the value in the array (which could be html, another component rendered using output buffering etc)
                    </td>
                </tr>
            </table>
        </li>
        
    </ul>

    <h2>Let's review order of operations</h2>
    
    <ol>
        <li><strong>html/index.php is hit</strong> &HorizontalLine; core is initialized (reads route config, instantiates corresponding controller)</li>
        <li><strong>app/controllers/example/view/Home.php</strong> &HorizontalLine; Where the class declaration for Home is defined, extending core route classes, and one custom extender.
        
            <br>
            <br>
            
            <strong>Route Extender Order in this case:</strong>
            
            <ul>
                <li><strong>app/controllers/routeExtenders/ExampleRouteExtender.php</strong> &HorizontalLine; Custom route extender to put methods relating to routing for the "Example" controller bundle.  Write whatever is useful to your bundle, extending one of the core routeExtenders.</li>
                <li><strong>app/controllers/core/routeExtenders/DataRouteExtender.php</strong> &HorizontalLine; A core route extender providing methods for easily connecting to the Db classes. Don't really need this extender on the home page since we aren't using any data. Could have used ExtendedRouteExtender</li>
                <li><strong>app/controllers/core/routeExtenders/ExtendedRouteExtender.php</strong> &HorizontalLine; Extra methods for dealing with almost all types of controller needs - loads format and validate services, the nav utility, calls custom methods.
                <li><strong>app/controllers/core/routeExtender.php</strong> &HorizontalLine; the OG of routes, has the constructor, renders, applies tokens, has theme management methods etc. At the very least, route controllers must extend this class.</li>
            </ul>
        
        </li>
        
        <li><strong>app/controllers/example/view/Home.php</strong> &HorizontalLine; route controllers must have init method and usually override the render method.</li>
        <li><strong>init calls render</strong> &HorizontalLine; which takes a path, data, and tokens.</li>
    </ol>

</div>