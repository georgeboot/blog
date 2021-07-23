<?php

// @var $container \Illuminate\Container\Container
// @var $events \TightenCo\Jigsaw\Events\EventBus

/*
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 */

$events->afterBuild(App\Listeners\GenerateSitemap::class);
$events->afterBuild(App\Listeners\GenerateIndex::class);


$container->singleton('markdownParser', function ($c) {
    return new class extends TightenCo\Jigsaw\Parsers\MarkdownParser {
        /** @var \League\CommonMark\CommonMarkConverter */
        public $parser;

        public function __construct()
        {
            $this->parser = new \League\CommonMark\CommonMarkConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
        }
        
        public function parse($text)
        {
            return $this->parser->convertToHtml($text);
        }
    };
});
