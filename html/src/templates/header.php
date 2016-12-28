<!DOCTYPE html>
<html>
    [bc:head]

    <body class="[bc:routeExtender]">
        <header>
            <nav>
                <div class="container">
                    <div class="logo col-xs-3">
                        <a href="/">
                            <img src="<?php echo \Bc\App\Util::getImage('logo.png'); ?>"/>
                        </a>
                    </div>
                    <div class="menu col-xs-9">
                        [bc:nav]
                    </div>
                </div>
            </nav>
            <div id="top" class="hero-unit">
                [bc:slogan]
            </div>
        </header>