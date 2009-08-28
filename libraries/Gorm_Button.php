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
class Gorm_Button_Core extends Gorm_Element_Core {

    protected function _render()
    {
        return form::button(
            $this->attributes['name'],
            @$this->attributes['value'],
            $this->_parse_attributes()
        );
    }

}