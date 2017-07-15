<div class="full-width-hero">
    <div class="hero-text">
        <div>
            Camouflaged for the Jungle;
        </div>
        <div>
            Indifferent like a Cat.
        </div>
    </div>
</div>
<div class="container">
    <h1>Install Route is Working</h1>
    <p>
        You should try to <a href="/docs/themes/create/">create your own theme</a>.
    </p>
    <p>For now, <strong>practice switching this route</strong> to use the <strong>Home</strong> controller.</p>
    <ol>
        <li>
            Switch "Installed" for "Home" in <code>app/config/routes.php</code>
            
            <pre class="codebox" data-plugin="codebox" data-title="app/config/routes.php - With / using Installed">/* Views */
 '/' => '\Bc\App\Controllers\Example\View\Installed',</pre>
            
            to
            
            <pre class="codebox" data-plugin="codebox" data-title="app/config/routes.php - With / using Home">/* Views */
 '/' => '\Bc\App\Controllers\Example\View\Home',</pre>
        </li>
        <li>Notice that the corresponding controller is in <code>app/controllers/example/view/Home.php according to the route config.</code></li>
    </ol>

</div>