<div class="container">
    <h1>Install Route is Working</h1>
    <p>
        You should try to create your own home page.
    </p>
    <ol>
        <li>
            Switch "Install" for "Home" in <code>app/config/routes.php</code>
        </li>
        <li>
            Make sure there's a Home.php and Home class with an init() method in 
            <code>app/classes/</code>
            <pre>
namespace Bc\App\Classes;

class Home extends \Bc\App\RouteClass {

    protected function init()
    {
        $this->render(SRC_DIR . 'home.php');
    }
}    
            </pre>
        </li>
        <li>
            Edit/Add a template file for home.php in <code>html/src/main/</code>
        </li>
    </ol>
    
    <hr/>
    
    <h2>Edit the head, header, and footer templates</h2>
    <p>You can edit these other templates that get rendered in the `Home` class.</p>
    
    <ol>
        <li><code>html/src/templates/</code>head|header|footer.php are all default templates, so changing these will change them everywhere.</li>
        <li>
            You can make custom head, headers, footers and load them with customized `init()` method
        
            <pre>
namespace Bc\App\Classes;

class Articles extends \Bc\App\RouteClass {

    protected function init()
    {
        $this->setHead(SRC_DIR . 'templates/head-articles.php');
        $this->setHeader(SRC_DIR . 'templates/header-minimal.php');
        $this->setFooter(SRC_DIR . 'templates/footer-with-cta.php');
        $this->render(SRC_DIR . 'articles.php');
    }
}
            </pre>
        </li>
        
    </ol>
</div>