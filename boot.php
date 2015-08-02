<?php

namespace extensions\minifier;

/* Prevent direct access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];

$adapt->on(
    'adapt.ready',
    function($event){
        $adapt = $event['object'];
        
        if ($adapt->dom && $adapt->dom instanceof \frameworks\adapt\html){
            $css = $adapt->dom->find('head link[type="text/css"]');
            $js = $adapt->dom->find('head script[type="text/javascript"]');
            
            
            if ($css->size() > 0){
                $key = 'minifier.css:';
                
                for($i = 0; $i < $css->size(); $i++){
                    $href = $css->get($i)->attr('href');
                    $path = $_SERVER['DOCUMENT_ROOT'] . $href;
                    if (preg_match("/\.css$/i", $href) && file_exists($path)){
                        $key .= $href;
                        $css->get($i)->add_class('minifier-detachable');
                    }
                }
                
                $cached_css = $adapt->cache->get($key);
                
                if (is_null($cached_css)){
                    /* We don't have a cached version */
                    
                    /*
                     * We need to minify the css and this could
                     * take a while depending on how many css files
                     * we have to process, so instead we are just
                     * going to do one per request.  Once all the
                     * files are minified we will replace them with
                     * a single minfied css file.
                     */
                    
                    $css_data = "";
                    
                    for($i = 0; $i < $css->size(); $i++){
                        $href = $css->get($i)->attr('href');
                        $path = $_SERVER['DOCUMENT_ROOT'] . $href;
                        if (preg_match("/\.css$/i", $href) && file_exists($path)){
                            /* Only minify real files */
                            
                            $css_data_raw = file_get_contents($path);
                            
                            if ($css_data_raw){
                                /* Minify */
                                $css_data .= minify::css($css_data_raw);
                            }
                        }
                        
                    }
                    
                    if ($css_data){
                        $adapt->cache->css($key, $css_data, 600, true);
                    }
                }
                
                /* Detach the css links */
                $adapt->dom->find('head .minifier-detachable')->detach();
                
                $key = md5($key);
                $adapt->dom->find('head')->append(new html_link(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => '/adapt/frameworks/adapt/store/public/' . $key)));
            }
            
        }
        
    }
);


?>