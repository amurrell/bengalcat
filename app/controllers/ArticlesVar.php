<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\SiteRouteExtender;
use Bc\App\Util;

class ArticlesVar extends SiteRouteExtender {

    protected function init()
    {
        $this->addQuery(
            'article',              // name of query and property on $this->data, ie $this->data->article
            'articleQueries',       // name of queries object to use  where the queries are located - (look at RouteExtenders\SiteRouteExtender)
            'selectArticleById',    // name of the query method on the queries object to use
            [
                'article_id' => $this->getVariant() // data the query method will need
            ],
            false,                  // not a collection, return back flat object
            'article',              // a name space for formatting fields from this dataset (see formatService)
            true                    // require data set or return 404.
        );

        // This next call is what hits the database with $this->queries.
        // Add more queries before this, or add some after this (remembering to call this again to actually run the new queries).
        // All data, from all queries, found on $this->data.
        $this->prepareData();
        $this->prepareTokens();
        $this->render(SRC_DIR . 'main/article.php', $this->data, $this->tokens);
    }

    protected function prepareTokens()
    {
        $this->tokens = [
            '[bc:slogan]' => 'Testing an Article'
        ];

        return $this;
    }
}

