<?php

namespace adapt\minifier{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_minifier extends \adapt\bundle{
        
        public function __construct($data){
            parent::__construct('minifier', $data);
        }
        
        public function boot(){
            if (parent::boot()){
                

                
                return true;
            }
            
            return false;
        }
        
    }
    
    
}

?>