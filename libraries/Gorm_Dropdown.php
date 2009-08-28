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
class Gorm_Dropdown_Core extends Gorm_Field {

    /**
     * Options of the dropdown
     *
     * @access protected
     * @var array
     */
    protected $options = array();

    /**
     * Selected options of the dropdown
     *
     * @access protected
     * @var array
     */
    protected $selected = array();

    /**
     * Makes the dropdown multiple
     *
     * @return void
     */
    public function set_multiple()
    {
        $this->attributes['multiple'] = 'multiple';
    }

    /**
     * Sets the elements as selected
     *
     * @param string ... names of the options to be selected
     * @return void
     */
    public function set_selected()
    {
        $selected = func_get_args();
        $this->selected = count($selected) == 1 ? $selected[0] : $selected;
    }

    /**
     * Sets the options of the dropdown element
     *
     * @return void
     */
    public function set_options($options)
    {
        $this->options = $options;
    }

    protected function _render()
    {
        $attr = array('name' => $this->attributes['name']);
        $multiple = arr::remove('multiple', $this->attributes);
        if($multiple) {
            $attr = array_merge($attr, array('multiple' => $multiple));
        }
        return form::dropdown(
            $attr,
            $this->options,
            $this->selected,
            $this->_parse_attributes()
        );
    }

}