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

class MultipleSelect extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareOptionsTab();
    	$this->prepareAdvancedTab();
    	return $this;
    }

    /**
     * @return Cytracon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareOptionsTab()
    {
        $options = $this->addTab(
            'tab_options',
            [
                'sortOrder'       => 40,
                'templateOptions' => [
                    'label' => __('Options')
                ]
            ]
        );

        	$items = $options->addChildren(
                'options',
                'dynamicRows',
                [
					'key'       => 'options',
					'className' => 'mgz-dynamicrows-table',
					'sortOrder' => 10
                ]
            );

            	$container1 = $items->addContainerGroup(
	                'container1',
	                [
						'templateOptions' => [
	                        'sortOrder' => 10
	                    ]
	                ]
	            );

	            	$container1->addChildren(
			            'label',
			            'text',
			            [
			                'key'             => 'label',
			                'sortOrder'       => 10,
			                'templateOptions' => [
								'label' => __('Label')
			                ]
			            ]
			        );

	            	$container1->addChildren(
			            'value',
			            'text',
			            [
			                'key'             => 'value',
			                'sortOrder'       => 20,
			                'templateOptions' => [
								'label' => __('Value')
			                ]
			            ]
			        );

	            	$container1->addChildren(
			            'default',
			            'checkbox',
			            [
							'className'       => 'mgz_center mgz-width30',
							'key'             => 'default',
							'sortOrder'       => 30,
							'templateOptions' => [
								'label' => __('Default')
			                ]
			            ]
			        );

	            	$container4 = $container1->addContainer(
		                'container4',
		                [
							'className' => 'mgz-dynamicrows-actions',
							'sortOrder' => 40
		                ]
		            );

		            	$container4->addChildren(
				            'delete',
				            'actionDelete',
				            [
								'sortOrder' => 10
				            ]
				        );

		            	$container4->addChildren(
				            'position',
				            'text',
				            [
								'sortOrder'       => 20,
								'key'             => 'position',
								'templateOptions' => [
									'element' => 'Cytracon_Builder/js/form/element/dynamic-rows/position'
								]
				            ]
				        );

        return $options;
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
	                'shuffle',
	                'toggle',
	                [
						'sortOrder'       => 20,
						'key'             => 'shuffle',
						'templateOptions' => [
	                        'label' => __('Shuffle Options')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                'options_height',
	                'number',
	                [
						'sortOrder'       => 30,
						'key'             => 'options_height',
						'templateOptions' => [
	                        'label' => __('Options Height(px)')
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
	                'others',
	                'toggle',
	                [
						'sortOrder'       => 10,
						'key'             => 'others',
						'templateOptions' => [
	                        'label' => __('Show Others Option')
	                    ]
	                ]
	            );

            	$container2->addChildren(
	                'others_label',
	                'text',
	                [
						'sortOrder'       => 20,
						'key'             => 'others_label',
						'defaultValue'    => 'Others',
						'templateOptions' => [
	                        'label' => __('Others Label')
	                    ],
						'hideExpression' => '!model.others'
	                ]
	            );

            	$container2->addChildren(
	                'others_description',
	                'text',
	                [
						'sortOrder'       => 30,
						'key'             => 'others_description',
						'templateOptions' => [
	                        'label' => __('Others Description')
	                    ],
						'hideExpression' => '!model.others'
	                ]
	            );

        return $advanced;
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
    	return [
    		'options' => [
    			[
					'label'    => 'Option1',
					'position' => 1
    			],
    			[
					'label'    => 'Option2',
					'position' => 2
    			],
    			[
					'label'    => 'Option3',
					'position' => 3
    			],
    			[
					'label'    => 'Option4',
					'position' => 4
    			],
    			[
					'label'    => 'Option5',
					'position' => 5
    			]
    		]
    	];
    }
}