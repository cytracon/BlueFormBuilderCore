<?php
/**
 * Cytracon
 *
 * This source file is subject to the Cytracon Software License, which is available at https://www.cytracon.com/license.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.cytracon.com for more information.
 *
 * @category  BlueFormBuilder
 * @package   Cytracon_BlueFormBuilderCore
 * @copyright Copyright (C) 2019 Cytracon (https://www.cytracon.com)
 */

namespace Cytracon\BlueFormBuilderCore\Data\Element;

class Text extends AbstractElement
{
	/**
	 * @var \BlueFormBuilder\BlueFormBuilder\Model\Source\Validation
	 */
	protected $validation;

	/**
	 * @param \Cytracon\Builder\Data\FormFactory             $formFactory   
	 * @param \Cytracon\Builder\Helper\Data                  $builderHelper 
	 * @param \Cytracon\BlueFormBuilderCore\Model\Source\Validation $validation    
	 * @param array                                         $data          
	 */
    public function __construct(
        \Cytracon\Builder\Data\FormFactory $formFactory,
        \Cytracon\Builder\Helper\Data $builderHelper,
        \Cytracon\BlueFormBuilderCore\Model\Source\Validation $validation,
        array $data = []
    ) {
    	parent::__construct($formFactory, $builderHelper, $data);
    	$this->validation = $validation;
    }

    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareIconTab();
    	$this->prepareValidationTab();
    	$this->prepareAdvancedTab();
    	return $this;
    }

    /**
     * @return Cytracon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareValidationTab()
    {
        $validation = $this->addTab(
            'tab_validation',
            [
                'sortOrder'       => 30,
                'templateOptions' => [
                    'label' => __('Validation')
                ]
            ]
        );

        	$container1 = $validation->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    'min',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min',
                        'templateOptions' => [
                            'label' => __('Min')
                        ]
                    ]
                );

                $container1->addChildren(
                    'max',
                    'number',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max',
                        'templateOptions' => [
                            'label' => __('Max')
                        ]
                    ]
                );

                $container1->addChildren(
                    'limit_by',
                    'select',
                    [
						'sortOrder'       => 30,
						'key'             => 'limit_by',
						'defaultValue'    => 'characters',
						'templateOptions' => [
                            'label'   => __('Limit By'),
                            'options' => $this->getLimitByOptions()
                        ]
                    ]
                );

            $validation->addChildren(
                'validation',
                'uiSelect',
                [
					'sortOrder'       => 20,
					'key'             => 'validation',
					'templateOptions' => [
                        'label'   => __('Validation'),
                        'options' => $this->validation->toOptionArray()
                    ]
                ]
            );

            $validation->addChildren(
                'limit_message',
                'text',
                [
					'sortOrder'       => 30,
					'key'             => 'limit_message',
					'defaultValue'    => 'Character(s) left',
					'templateOptions' => [
                        'label' => __('Text To Appear After Counter')
                    ]
                ]
            );

            $validation->addChildren(
                'show_count',
                'toggle',
                [
					'sortOrder'       => 40,
					'key'             => 'show_count',
					'templateOptions' => [
                        'label' => __('Show Character Count')
                    ]
                ]
            );

        return $validation;
    }

    /**
     * @return Cytracon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareAdvancedTab()
    {
        $advanced = $this->addTab(
            'tab_advanced',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Advanced')
                ]
            ]
        );

        	$container1 = $advanced->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

            	$container1->addChildren(
	                self::FIELD_REQUIRED,
	                'toggle',
	                [
						'sortOrder'       => 10,
						'key'             => self::FIELD_REQUIRED,
						'templateOptions' => [
	                        'label' => __('Required Field')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                self::FIELD_HIDDEN,
	                'toggle',
	                [
						'sortOrder'       => 20,
						'key'             => self::FIELD_HIDDEN,
						'templateOptions' => [
	                        'label' => __('Hidden Field')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                self::FIELD_READONLY,
	                'toggle',
	                [
						'sortOrder'       => 30,
						'key'             => self::FIELD_READONLY,
						'templateOptions' => [
	                        'label' => __('Read-Only Field')
	                    ]
	                ]
	            );

            $container2 = $advanced->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 20
                ]
            );

                $container2->addChildren(
                    self::FIELD_AUTOTOFOCUS,
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => self::FIELD_AUTOTOFOCUS,
                        'templateOptions' => [
                            'label'        => __('Autofocus'),
                            'tooltipClass' => 'tooltip-bottom tooltip-bottom-right',
                            'tooltip'      => __('When present, it specifies that the element should automatically get focus when the page loads.')
                        ]
                    ]
                );

                $container2->addChildren(
                    self::FIELD_AUTOTOMPLETE,
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_AUTOTOMPLETE,
                        'templateOptions' => [
                            'label' => __('Browser Autocomplete')
                        ]
                    ]
                );

	    	$advanced->addChildren(
	            self::FIELD_DEFAULT_VALUE,
	            'text',
	            [
                    'sortOrder'       => 30,
                    'key'             => self::FIELD_DEFAULT_VALUE,
                    'templateOptions' => [
                        'templateUrl' => 'Cytracon_BlueFormBuilderCore/js/templates/modal/form/element/smart_variables.html',
                        'element'     => 'Cytracon_BlueFormBuilderCore/js/modal/element/smart-fields',
                        'label'       => __('Default Value')
	                ]
	            ]
	        );

	    	$advanced->addChildren(
	            self::FIELD_PLACEHOLDER,
	            'text',
	            [
					'sortOrder'       => 40,
					'key'             => self::FIELD_PLACEHOLDER,
					'templateOptions' => [
	                    'label' => __('Placeholder')
	                ]
	            ]
	        );

            $advanced->addChildren(
                'input_mask',
                'text',
                [
                    'sortOrder'       => 50,
                    'key'             => 'input_mask',
                    'templateOptions' => [
                        'label' => __('Input Mask')
                    ]
                ]
            );

	    	$advanced->addChildren(
	            'auto_suggest',
	            'textarea',
	            [
					'sortOrder'       => 60,
					'key'             => 'auto_suggest',
					'templateOptions' => [
                        'label' => __('Auto Suggest'),
                        'rows'  => 5,
                        'note'  => __('Seperate the suggests with new line.')
	                ]
	            ]
	        );

        return $advanced;
    }
    
    /**
     * @return array
     */
    protected function getLimitByOptions()
    {
        return [
            [
                'label' => __('Characters'),
                'value' => 'characters'
            ],
            [
                'label' => __('Words'),
                'value' => 'words'
            ]
        ];
    }
}