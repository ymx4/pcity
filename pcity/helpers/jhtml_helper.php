<?php

function get_select_options($dataArr,$key,$value,$selectedValue = '') {
    
    $str = '';
    foreach ($dataArr as $data){
        $selected = $data[$key] == $selectedValue ? ' selected ' : '';
        $str .= '<option value="'.$data[$key].'"'.$selected.'>'.$data[$value].'</option>';
    }
    
    return $str;
}

function get_select_optionsByArr($dataArr,$selectedValue = '') {
     
    $str = '';
    foreach ($dataArr as $key=>$data){
        $selected = $key == $selectedValue ? 'selected' : '';
        $str .= '<option value="'.$key.'" '.$selected.'>'.$data.'</option>';
    }
     
    return $str;
}
