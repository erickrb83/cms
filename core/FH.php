<?php
namespace Core;

class FH{
    public static function inputBlock($label, $id, $value, $inputAttributes = [], $wrapperAttributes = [], $errors = []){
        $wrapperStr = self::processAttributes($wrapperAttributes);
        $inputAttributes = self::appendErrors($id, $inputAttributes, $errors);
        $inputAttributes = self::processAttributes($inputAttributes);
        $errorMsg = array_key_exists($id, $errors) ? $errors[$id] : "";
        $html = "<div {$wrapperStr}>";
        $html .= "<label for='{$id}'>{$label}</label>";
        $html .= "<input id='{$id}' name='{$id}' value='{$value}' {$inputAttributes}/>";
        $html .= "<div class='invalid-feedback'>{$errorMsg}</div></div>";
        return $html;
    }

    public static function selectBlock($label, $id, $value, $options, $inputAttributes = [], $wrapperAttributes = [], $errors = []){
        $inputAttributes = self::appendErrors($id, $inputAttributes, $errors);
        $inputAttributes = self::processAttributes($inputAttributes);
        $wrapperStr = self::processAttributes($wrapperAttributes);
        $errorMsg = array_key_exists($id, $errors) ? $errors[$id] : "";
        $html = "<div {$wrapperStr}>";
        $html .= "<label for= '{$id}'>{$label}</label>";
        $html .= "<select id='{$id}' name='{$id}' {$inputAttributes}>";
        foreach($options as $val => $display){
            $selected = $val == $value ? ' selected ' : '';
            $html .= "<option value='{$val}'{$selected}>{$display}</option>";
        }
        $html .= "</select>";
        $html .= "<div class='invalid-feedback'>{$errorMsg}</div></div>";
        return $html;
    }

    public static function check($label, $id, $checked = '', $inputAttributes = [], $wrapperAttributes = [], $errors = []){
        $inputAttributes = self::appendErrors($id, $inputAttributes, $errors);
        $wrapperStr = self::processAttributes($wrapperAttributes);
        $inputStr = self::processAttributes($inputAttributes);
        $checkedStr = $checked == 'on' ? "checked" : "";
        $html = "<div {$wrapperStr}>";
        $html .= "<input type=\"checkbox\" id=\"{$id}\" name=\"{$id}\" {$inputStr} {$checkedStr}>";
        $html .= "<label class=\"form-check-label\" for=\"{$id}\"></label>{$label}</div>";
        return $html;
    }

    public static function appendErrors($key, $inputAttributes, $errors){
        if(array_key_exists($key, $errors)){
            if(array_key_exists('class', $inputAttributes)){
                $inputAttributes['class'] .= ' is-invalid';
            }else{
                $inputAttributes['class'] = 'is-invalid';
            }
        }
        return $inputAttributes;
    }

    public static function processAttributes($attributes){
        $html = "";
        foreach($attributes as $key => $value){
            $html .= " {$key}='{$value}'";
        }
        return $html;
    }

    public static function csrfField(){
        $token = Session::createCsrfToken();
        $html = "<input type='hidden' value='{$token}' name='csrfToken' />";
        return $html;
    }
}