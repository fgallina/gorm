<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Gorm: Kohana's form generation library
 *
 * @package Gorm
 * @author Fabián Ezequiel Gallina
 * @copyright 2009 Fabián Ezequiel Gallina
 * @license http://kohanaphp.com/license.html
 */
class Gorm_Core extends Controller_Core {


    /**
     * Unique id of the form object
     *
     * @access protected
     * @var string
     */
    protected $unique_id = '';

    /**
     * The name of the view used to render the form
     *
     * @access protected
     * @var string
     */
    protected $view = '';

    /**
     * The attributes of the form
     *
     * @access protected
     * @var array
     */
    protected $attributes = array();

    /**
     * Array containing the fields of the form
     *
     * @access protected
     * @var array
     */
    protected $fields = array();

    /**
     * Array containing the hidden fields of the form
     *
     * @access protected
     * @var array
     */
    protected $hidden_fields = array();

    /**
     * Array containing all the errors triggered by the validation
     *
     * @access protected
     * @var array
     */
    protected $errors = array();

    /**
     * Class construct
     *
     * @param string form action
     * @param string form method
     * @param array|bool if true the form enctype is set to
     * multipart/form-data else it is an array containing the form
     * attributes
     * @param string the view used to render the form
     * @param mixed unique id for the form if several instances
     * of Gorm are in the same page
     * @return void
     */
    public function __construct($action = '', $method = 'post', $attributes = array(), $view = '', $unique_id = '')
    {
        $this->view = $view;

        if(is_array($attributes)) {
            $this->attributes = $attributes;
        } elseif($attributes === TRUE) {
            $this->attributes['enctype'] = 'multipart/form-data';
        }

        $this->attributes['action'] = strtolower($action);
        $this->attributes['method'] = strtolower($method);

        $this->add_element('hidden', '_gorm_form_unique_id', $unique_id);
        $this->unique_id = (string) $unique_id;
        parent::__construct();
    }

    /**
     * Sets the unique id of the Gorm object
     *
     * @param mixed unique_id
     * @return void
     */
    protected function set_unique_id($unique_id)
    {
        $this->get_element('_gorm_form_unique_id')->set_value($unique_id);
        $this->unique_id = (string) $unique_id;
    }

    /**
     * Adds an element to the form
     *
     * @param string element type
     * @param string name of the element
     * @param string value of the element
     * @param array attributes of the element
     * @return object the Gorm_Field instance
     */
    public function add_element($type, $name, $value = FALSE, $attributes = array())
    {
        if($value === FALSE || $value === NULL) {
            $method = $this->attributes['method'];
            $value = Kohana::instance()->input->$method($name);
        }
        $input = 'Gorm_'.ucfirst($type);
        $element = new $input();
        $attributes = array_merge(
            array('name' => $name, 'value' => $value),
            $attributes);
        $element->set_attributes($attributes);
        $reference = &$element;
        if($type != 'hidden') {
            $this->fields[] = $reference;
        } else {
            $this->hidden_fields[] = $reference;
        }
        return $reference;
    }

    /**
     * Get's an existing element of the form
     *
     * @param string the name of the element
     * @throws Kohana_Exception if the element doesn't exists
     * @return object the Gorm_Field instance
     */
    public function get_element($name)
    {
        $elements = $this->get_elements();
        for($i = 0; $i < count($elements); $i++) {
            if($elements[$i]->get_attribute('name') == $name) {
                return $elements[$i];
            }
        }
        throw new Kohana_Exception('gorm.invalid_element_name', $name);
    }

    /**
     * Returns all the form elements
     *
     * @return array
     */
    public function get_elements()
    {
        return array_merge($this->fields, $this->hidden_fields);
    }

    /**
     * Renders the form
     *
     * @return void
     */
    public function render()
    {
        $action = arr::remove('action', $this->attributes);
        $out = '';
        foreach($this->hidden_fields as $field) {
            $out .= $field->render();
        }
        $open = form::open($action, $this->attributes);
        $open .= $out;
        $close = form::close();
        if(!$this->view) {
            $content = '';
            foreach($this->fields as $field) {
                $content .= $field->render();
            }
            $content = $open . $content . $close;
        } else {
            $view = new View($this->view);
            $view->open = $open;
            $view->fields = $this->fields;
            $view->errors = $this->errors;
            $view->close = $close;
            $content = $view->render();
        }
        return $content;
    }

    /**
     * Validates the form
     *
     * @see http://docs.kohanaphp.com/libraries/validation
     * @return bool true if valid
     */
    public function validate()
    {
        $to_validate = array();

        if($this->attributes['method'] == 'post') {
            $to_validate = $_POST;
        } else {
            $to_validate = $_GET;
        }

        if(@$to_validate['_gorm_form_unique_id'] == $this->unique_id) {
            unset($to_validate['_gorm_form_unique_id']);

            if(@$this->attributes['enctype'] == 'multipart/form-data') {
                $to_validate = array_merge($to_validate, $_FILES);
            }

            $to_validate = new Validation($to_validate);

            foreach($this->get_elements() as $field) {
                $name = $field->get_attribute('name');
                $rules = $field->get_rules();
                $pre_filters = $field->get_pre_filters();
                $post_filters = $field->get_post_filters();
                if($rules) {
                    foreach($rules as $rule) {
                        $to_validate->add_rules($name, $rule);
                    }
                }
                if($pre_filters) {
                    foreach($pre_filters as $filter) {
                        $to_validate->pre_filter($filter, $name);
                    }
                }
                if($post_filters) {
                    foreach($post_filters as $filter) {
                        $to_validate->post_filter($filter, $name);
                    }
                }
            }

            if($to_validate->validate()) {
                return TRUE;
            } else {
                $this->errors = $to_validate->errors();
                return FALSE;
            }
        }

    }

    /**
     * Returns all the errors triggered by the validation
     *
     * @return array
     */
    public function errors($field = FALSE)
    {
        if($field) {
            return $this->errors[$field];
        } else {
            return $this->errors;
        }
    }

    /**
     * Redefined method call to not throw a 404_Exception
     *
     * @return void
     */
    public function __call($method, $args)
    {

    }

}