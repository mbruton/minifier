<?php


namespace extensions\minifier{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class minify extends \frameworks\adapt\base{
        
        public static function css($css){
            
            $re1 = <<<'EOS'
(?sx)
  # quotes
  (
    "(?:[^"\\]++|\\.)*+"
  | '(?:[^'\\]++|\\.)*+'
  )
|
  # comments
  /\* (?> .*? \*/ )
EOS;

    $re2 = <<<'EOS'
(?six)
  # quotes
  (
    "(?:[^"\\]++|\\.)*+"
  | '(?:[^'\\]++|\\.)*+'
  )
|
  # ; before } (and the spaces after it while we're here)
  \s*+ ; \s*+ ( } ) \s*+
|
  # all spaces around meta chars/operators
  \s*+ ( [*$~^|]?+= | [{};,>~+-] | !important\b ) \s*+
|
  # spaces right of ( [ :
  ( [[(:] ) \s++
|
  # spaces left of ) ]
  \s++ ( [])] )
|
  # spaces left (and right) of :
  \s++ ( : ) \s*+
  # but not in selectors: not followed by a {
  (?!
    (?>
      [^{}"']++
    | "(?:[^"\\]++|\\.)*+"
    | '(?:[^'\\]++|\\.)*+' 
    )*+
    {
  )
|
  # spaces at beginning/end of string
  ^ \s++ | \s++ \z
|
  # double spaces to single
  (\s)\s+
EOS;

            $css = preg_replace("%$re1%", '$1', $css);
            $css = preg_replace("%$re2%", '$1$2$3$4$5$6$7', $css);
            
            return $css;
        }
        
    }
}

?>