<?php
/**
 * Div helper
 * @todo Expand this function so that content can be passed into it.
 * @param type $attributes
 * @return type dom element
 */
    function div($attributes = array())
    {
        $div = '<div ';
        
        foreach($attributes as $key => $value)
        {
            $div .= $key.'="'.$value.'"';
        }
        
        return $div . '></div>';
    }
?>
