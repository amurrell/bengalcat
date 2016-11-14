<div class="container">

    <h1>Edit this template in html > src > main > home.php</h1>

    <p>All the following contents are edited in the file above.</p>

    <p>The top section was edited by sending a value for the slogan token (without spaces) <code>[ bc:slogan ]</code> through the <strong>Home Class init</strong>.</p>

<pre>
protected function init()
{

    $slogan = \Bc\App\Util::getTemplateContents(SRC_DIR . 'tokenHTML/slogan-home.php');

    $this->render(SRC_DIR . 'main/home.php', null,
            array('[ bc:slogan ]' => $slogan));
}
</pre>

    <p>The value sent can be a string, like in the About.php class, or you can pass it a tokenHTML template piece from the src > tokenHTML folder for longer html/php snippets. The code uses output buffering to render these snippets.</p>

    <p>The slogan is rendered because the slogan token appears in the default header template. If I were to write it here too, it would render again.</p>

    <div class="well well-large">
        [bc:slogan]
    </div>

    <p>You can always change the default header to use different tokens or no tokens at all.</p>

    <p>You can also make your own header and use the Home Class init function to set that header template instead.</p>

    <h4>Here's an example of setting your own template parts for head, header, footer - from the <strong>Articles.php</strong> class:</h4>

<pre>
class Articles extends \Bc\App\RouteExtender {

    protected function init()
    {
        /** @note Example of custom head, header, footer */
        $this->setHead(SRC_DIR . 'templates/head-articles.php');
        $this->setHeader(SRC_DIR . 'templates/header-minimal.php');
        $this->setFooter(SRC_DIR . 'templates/footer-with-cta.php');
        $this->render(SRC_DIR . 'articles.php');
    }
}
</pre>

    <p>You can put tokens anywhere you want in your templates, the head, footer, header, or your views.</p>

    <p>If you do not supply a value for the tokens, then nothing will render for that token.</p>

    <p>If you would like there to be a default value, you can use the app > config > tokenDefaults.php file (look at the sample included in the framework).</p>

</div>