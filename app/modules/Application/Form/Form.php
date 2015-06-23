<?php

/**
 * Form
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Form;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\File;
use Application\Form\Element\Image;

abstract class Form extends \Phalcon\Forms\Form
{

    public function renderDecorated($name)
    {
        if (!$this->has($name)) {
            return "form element '$name' not found<br />";
        }

        $element = $this->get($name);

        /** @var \Application\Mvc\Helper $helper */
        $helper = $this->getDI()->get('helper');

        $messages = $this->getMessagesFor($element->getName());

        $html = '';

        if (count($messages)) {
            $html .= '<div class="ui error message">';
            $html .= '<div class="header">' . $this->helper->translate('Ошибка валидации формы') . '</div>';
            foreach ($messages as $message) {
                $html .= '<p>' . $message . '</p>';
            }
            $html .= '</div>';
        }

        if ($element instanceof Hidden) {
            echo $element;
        } else {
            switch (true) {
                case $element instanceof Check :
                {
                    $html .= '<div class="field checkbox">';
                    $html .= '<div class="ui toggle checkbox">';
                    $html .= $element;
                    if ($element->getLabel()) {
                        $html .= '<label>';
                        $html .= $element->getLabel();
                        $html .= '</label>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                }
                    break;
                case $element instanceof Image : {
                    $html = $this->renderImage($element);
                }
                    break;
                case $element instanceof File :
                {
                    $html .= '<div class="inline field">';
                    if ($element->getLabel()) {
                        $html .= '<label for="' . $element->getName() . '">' . $helper->translate($element->getLabel()) . '</label>';
                    }
                    $html .= $element;
                    $html .= '</div>';
                }
                    break;
                default :
                    {
                    $html .= '<div class="field">';
                    if ($element->getLabel()) {
                        $html .= '<label for="' . $element->getName() . '">' . $helper->translate($element->getLabel()) . '</label>';
                    }
                    $html .= $element;
                    $html .= '</div>';
                    }
            }
        }

        return $html;

    }

    public function renderAll()
    {
        $html = '';
        if ($this->getElements()) {
            foreach ($this->getElements() as $element) {
                $html .= $this->renderDecorated($element->getName());
            }
        }
        return $html;
    }

    /**
     * @param Image $element
     */
    private function renderImage($element)
    {
        $html = '<div class="form-group">';

        if ($element->getLabel()) {
            $html .= '<label>' . $element->getLabel() . '</label>';
        }
        if ($element->getValue()) {
            $html .= '<section onclick="selectText(this);">' . $element->getValue() . '</section>';
        } else {
            $html .= '<br>';
        }

        $html .= '<div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                 style="width: 200px; min-height: 100px">';

        if ($element->getValue()) {
            $html .= '<img src="' . $element->getValue() . '" width="200">';
        }

        $html .= '</div>
                        <div>
                            <span class="btn btn-default btn-file">
                                <span class="fileinput-new">Select image</span>
                                <span class="fileinput-exists">Change</span>
                                <input type="file" name="' . $element->getName().'">
                            </span>
                            <a href="#" class="btn btn-default fileinput-exists"
                               data-dismiss="fileinput">Remove</a>
                        </div>
                    </div>
                </div>';

        return $html;
    }

}
