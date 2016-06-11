<!DOCTYPE html>
<html>
    [bc:head]
    
    <body class="[bc:routeClass]">
        <header>
            <nav>
                <div class="container">
                    <div class="logo col-xs-3">
                        <a href="/">
                            <img src="<?= \Bc\App\Util::getImage('logo.png'); ?>"/>
                        </a>
                    </div>
                    <div class="menu col-xs-9">
                        <ul>
                            <li>
                                <a href="https://github.com/amurrell/bengalcat/blob/master/README.md">Docs</a>
                            </li>
                            <li>
                                <a href="/about/">About</a>
                            </li>
                            <li>
                                <a href="https://github.com/amurrell/bengalcat/">Download</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div id="top" class="hero-unit">
                [bc:slogan]
            </div>
        </header>