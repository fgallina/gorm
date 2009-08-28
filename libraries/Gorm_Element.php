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
abstract class Gorm_Element_Core {

    /**
     * Attributes of the element
     *
     * @access protected
     * @var array
     */
    protected $attributes = array();

    /**
     * Validation rules for the element
     *
     * @access protected
     * @var array
     */
    protected $validation_rules = array();

    /**
     * Pre filters to be applied to the element
     *
     * @access protected
     * @var array
     */
    protected $pre_filters = array();

    /**
     * Post filters to be applied to the element
     *
     * @access protected
     * @var array
     */
    protected $post_filters = array();

    /**
     * Content to render before the element
     *
     * @access protected
     * @var string
     */
    protected $pre_content = '';

    /**
     * Content to render after the element
     *
     * @access protected
     * @var string
     */
    protected $post_content = '';

    /**
     * Content to be rendered between the pre_content and the element itself
     *
     * @access protected
     * @var string
     */
    protected $label = '';

    /**
     * The type of the element
     *
     * @access protected
     * @var string
     */
    protected $type = '';

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->type = strtolower(substr(get_class($this), 5));
    }

    /**
     * Sets an attribute
     *
     * @return void
     */
    public function set_attribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * Gets an attribute
     *
     * @return void
     */
    public function get_attribute($attribute)
    {
        return $this->attributes[$attribute];
    }

    /**
     * Gets the name of the element
     *
     * @return string
     */
    public function get_name()
    {
        return $this->attributes['name'];
    }

    /**
     * Sets several attributes at once (key => value)
     *
     * @return void
     */
    public function set_attributes($attributes)
    {
        foreach($attributes as $attribute => $value) {
            $this->attributes[$attribute] = $value;
        }
    }

    /**
     * Gets all the attributes of the element
     *
     * @return array
     */
    public function get_attributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the pre_content of the element
     *
     * @return object the element instance
     */
    public function set_pre_content($content)
    {
        $this->pre_content = $content;
        return $this;
    }

    /**
     * Sets the post_content of the element
     *
     * @return object the element instance
     */
    public function set_post_content($content)
    {
        $this->post_content = $content;
        return $this;
    }

    /**
     * Adds validation rules to the element
     *
     * @see http://docs.kohanaphp.com/libraries/validation
     * @param string ... names of the callbacks to validate the element
     * @return object the element instance
     */
    public function add_rules()
    {
        $this->validation_rules = array_merge(
            func_get_args(),
            $this->validation_rules
        );
        return $this;
    }

    /**
     * Adds a filter to be applied before validation
     *
     * @see http://docs.kohanaphp.com/libraries/validation
     * @param string ... names of the callbacks to manipulate the element
     * @return object the element instance
     */
    public function pre_filter()
    {
        $this->pre_filters = array_merge(
            func_get_args(),
            $this->pre_filters
        );
        return $this;
    }

    /**
     * Adds a filter to be applied after validation
     *
     * @see http://docs.kohanaphp.com/libraries/validation
     * @param string ... names of the callbacks to manipulate the element
     * @return object the element instance
     */
    public function post_filter()
    {
        $this->post_filters = array_merge(
            func_get_args(),
            $this->post_filters
        );
        return $this;
    }

    /**
     * Gets the validation rules of the element
     *
     * @return object the element instance
     */
    public function get_rules()
    {
        return $this->validation_rules;
    }

    /**
     * Gets the validation rules of the element
     *
     * @return object the element instance
     */
    public function get_pre_filters()
    {
        return $this->pre_filters;
    }

    /**
     * Gets the validation rules of the element
     *
     * @return object the element instance
     */
    public function get_post_filters()
    {
        return $this->post_filters;
    }

    /**
     * Sets the label for the element
     *
     * @return object the element instance
     */
    public function set_label($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Gets the label for the element
     *
     * @return string
     */
    public function get_label()
    {
        return $this->label;
    }

    /**
     * Gets the label for the element
     *
     * @return string
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Parses the attributes of the element to a string
     *
     * @access protected
     * @return string
     */
    protected function _parse_attributes()
    {
        $attributes = '';
        foreach($this->attributes as $attribute => $value) {
            if($attribute != 'name' && $attribute != 'value') {
                $attributes .= $attribute . '= "' . $value . '"';
            }
        }
        return $attributes;
    }

    /**
     * Renders the final output for the element
     *
     * @return string
     */
    public function render()
    {
        $out = $this->pre_content . $this->_render() . $this->post_content;
        $out = str_replace("{label}", $this->label, $out);
        return $out;
    }

    /**
     * Renders the error string
     *
     * @param array errors triggered by the validation library
     * @return string the internationalized string
     */
    public function render_error($errors)
    {
        return Kohana::lang(
            'gorm_validation.'
            . $this->get_name()
            . '.' . $errors[$this->get_name()]
        );
    }

    /**
     * Renders the specific content for the element instance
     *
     * @access protected
     * @return string
     */
    abstract protected function _render();

}