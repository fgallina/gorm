<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Form test controller. This controller should NOT be used in production.
 * It is for demonstration purposes only!
 *
 * @package    Form
 * @author     Fabián Ezequiel Gallina
 * @copyright  (c) 2009 Fabián Ezequiel Gallina
 * @license    http://kohanaphp.com/license.html
 */
class Form_Test_Controller {

	const ALLOW_PRODUCTION = FALSE;

	public function text()
	{
            $element = new Gorm_Text();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function hidden()
	{
            $element = new Gorm_Hidden();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function submit()
	{
            $element = new Gorm_Submit();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function button()
	{
            $element = new Gorm_Button();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function file()
	{
            $element = new Gorm_File();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function password()
	{
            $element = new Gorm_Password();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function textarea()
	{
            $element = new Gorm_Textarea();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function checkbox()
	{
            $element = new Gorm_Checkbox();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_selected();
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function radio()
	{
            $element = new Gorm_Radio();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_selected();
            $element->set_attributes($attributes);
            echo $element->render();
	}

	public function dropdown()
	{
            $element = new Gorm_Dropdown();
            $attributes = array(
                'name' => 'test',
                'id' => 'test',
            );
            $element->set_attributes($attributes);
            $options = array(
                '1' => 'One',
                '2' => 'Two',
                '3' => 'Three'
            );
            $element->set_multiple();
            $element->set_options($options);
            $element->set_selected('2');
            echo $element->render();
	}

        public function gorm()
        {
            $form = new Gorm('form_test/gorm', 'post', FALSE, 'form/default');

            $form->add_element('text', 'name', @$_POST['name'])
                ->set_label('name: ')
                ->add_rules('required');

            $form->add_element('text', 'email', @$_POST['email'])
                ->set_label('email: ')
                ->add_rules('required','valid::email');

            $form->add_element('submit', 'submit', 'send');

            $form->validate();

            echo $form->render();

            $form = new Gorm('form_test/gorm', 'post', FALSE, 'form/default', '2');

            $form->add_element('text', 'name2', @$_POST['name2'])
                ->set_label('name: ')
                ->add_rules('required');

            $form->add_element('text', 'email2', @$_POST['email2'])
                ->set_label('email: ')
                ->add_rules('required','valid::email');

            $form->add_element('submit', 'submit', 'send');

            $form->validate();

            echo $form->render();

            echo Kohana::debug($_POST);
        }

        public function file_upload()
        {
            $form = new Gorm('form_test/file_upload', 'post', TRUE, 'form/default');

            $form->add_element('file', 'image')
                ->set_label('File: ')
                ->add_rules('upload::required', 'upload::valid', 'upload::type[gif,jpg,png]', 'upload::size[1M]');

            $form->add_element('submit', 'submit', 'send');

            if($form->validate()) {
                $dir = DOCROOT.'media/uploads/';
                move_uploaded_file($_FILES['image']['tmp_name'], $dir.$_FILES['image']['name']);
                echo "The file has been uploaded";
            } else {
                echo $form->render();
            }
        }

}