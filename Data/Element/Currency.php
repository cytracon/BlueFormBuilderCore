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

class Currency extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();
        $this->prepareIconTab();
        $this->prepareAdvancedTab();
        return $this;
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

            $advanced->addChildren(
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

            $advanced->addChildren(
                self::FIELD_DEFAULT_VALUE,
                'text',
                [
                    'sortOrder'       => 20,
                    'key'             => self::FIELD_DEFAULT_VALUE,
                    'templateOptions' => [
                        'label' => __('Default Value')
                    ]
                ]
            );

            $advanced->addChildren(
                self::FIELD_PLACEHOLDER,
                'text',
                [
                    'sortOrder'       => 30,
                    'key'             => self::FIELD_PLACEHOLDER,
                    'templateOptions' => [
                        'label' => __('Placeholder')
                    ]
                ]
            );

        return $advanced;
    }

    public function getDefaultValues()
    {
        return [
            'show_icon'     => true,
            'icon'          => 'fas mgz-fa-dollar-sign',
            'placeholder'   => '0.00'
        ];
    }
}