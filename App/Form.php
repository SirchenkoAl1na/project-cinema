<?php

namespace App;

enum Method: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
    case PATCH = "PATCH";
}
class Form
{
    // fields ($data) may contain:
    // 1. input
    // 2. select
    // 3. textarea
    // 4. checkbox
    // 5. radio
    // 6. button
    // 7. file
    // 8. hidden    
    // fields: name, label, field_type, value, options, attr
    // options: array of options for select, radio, checkbox
    static function Build(array $data, string $action, string $button = "Зберегти", Method|string $method = Method::POST): void
    {
        $form = "";
        foreach ($data as $row) {
            $form .= "<div class='row'>";
            foreach ($row as $field) {
                if(is_array($field)){
                $field_html = "";
                switch ($field['field_type']) {
                    case 'input':
                        if (isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], $field['attr'], $field['value']);
                        } else if (!isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], $field['attr']);
                        } else if (isset($field['value']) && !isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], "", $field['value']);
                        } else {
                            $field_html = self::createInput($field['name'], $field['type']);
                        }
                        break;
                    case 'select':
                        if (isset($field['value']) && isset($field['attr'])) {
                        $field_html = self::createSelect($field['name'], $field['options'], $field['attr'], $field['value']);
                        } else if (!isset($field['value']) && isset($field['attr'])) {
                        $field_html = self::createSelect($field['name'], $field['options'], $field['attr']);
                        } else if (isset($field['value']) && !isset($field['attr'])) {
                        $field_html = self::createSelect($field['name'], $field['options'], "", $field['value']);
                        } else {
                        $field_html = self::createSelect($field['name'], $field['options']);
                        }
                        break;
                    case 'textarea':
                        if (isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createTextarea($field['name'], $field['attr'], $field['value']);
                        } else if (!isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createTextarea($field['name'], $field['attr']);
                        } else if (isset($field['value']) && !isset($field['attr'])) {
                            $field_html = self::createTextarea($field['name'], '', $field['value']);
                        } else {
                            $field_html = self::createTextarea($field['name']);
                        }
                        break;
                    default:
                        if (isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], $field['attr'], $field['value']);
                        } else if (!isset($field['value']) && isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], $field['attr']);
                        } else if (isset($field['value']) && !isset($field['attr'])) {
                            $field_html = self::createInput($field['name'], $field['type'], "", $field['value']);
                        } else {
                            $field_html = self::createInput($field['name'], $field['type']);
                        }
                        break;
                }
                $form .= "<div class='form-control'><label for='" . $field['name'] . "'>" . $field['label'] . "</label>$field_html</div>";
                }else{
                    $form.=$field;
                }
            }
            $form .= "</div>";
        }
        $form.=self::button($button);
        $method = is_string($method)?$method:$method->value;
        $legend = isset($legend) ? '<legend>' . $legend . '</legend>' : '';
        echo "<form action='$action' method='$method' enctype='multipart/form-data'>$legend$form</form>";
    }
    static function createInput(string $name, string $type, string $attr = "", string $value = "")
    {
        $value = isset($value) ? "value='$value'" : '';
        return "<input type='$type' name='$name' $attr/>";
    }
    static function createSelect($name, array $options, string $attr = "", string $value = "")
    {
        $select = "<select name='$name' $attr>";
        $select .= "<option value=''>Оберіть...</option>";
        foreach ($options as $key=>$option) {
            $selected = ($value == $key) ? "selected" : "";
            $select .= "<option value='$key' $selected>$option</option>";
        }
        $select .= "</select>";
        return $select;
    }

    static function createTextarea(string $name, string $attr = "", string $value = ""): string
    {
        return "<textarea name='$name' $attr>$value</textarea>";
    }
    static function button(string $text="Зберегти")
    {
        return "<div class='row j-c-end'><button class='button button-save'>$text</button></div>";
    }
}
