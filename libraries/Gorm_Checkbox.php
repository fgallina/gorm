<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Gorm Field: Object oriented form element generation
 *
 * @package Gorm
 * @subpackage Elements
 * @author Fabián Ezequiel Gallina
 * @copyright 2009 Fabián Ezequiel Gallina
 * @license http://kohanaphp.com/license.html
 */
class Gorm_Checkbox_Core extends Gorm_Element_Core {

    /**
     * Sets the element as selected
     *
     * @return void
     */
    public function set_selected()
    {
        $this->attributes['checked'] = 'checked';
    }

    protected function _render()
    {
        return form::checkbox(
            $this->attributes['name'],
            @$this->attributes['value'],
            FALSE,
            $this->_parse_attributes()
        );
    }

}