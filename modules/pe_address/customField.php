<?php
    function generateNumberField($focus, $field, $value, $view)
    {
        $html = '';
        if ($view == 'EditView') {
            $html .= '<input type="number" value="'.$value.'" min="0" max="10" name="'.$field.'" id="'.$field.'" />';
        }
        return $html;
    }
?>