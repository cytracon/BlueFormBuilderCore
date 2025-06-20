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

namespace Cytracon\BlueFormBuilderCore\Ui\Component\Form\Element;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;

class Builder extends \Cytracon\Builder\Ui\Component\Form\Element\Builder
{
    /**
     * @var \Cytracon\Builder\Data\Elements
     */
    protected $builderElements;

    /**
     * @param ContextInterface                      $context         
     * @param FormFactory                           $formFactory     
     * @param ConfigInterface                       $wysiwygConfig   
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory   
     * @param \Magento\Framework\Registry           $registry        
     * @param \Cytracon\Builder\Data\Elements        $builderElements 
     * @param array                                 $data            
     * @param array                                 $config          
     */
    public function __construct(
        ContextInterface $context,
        FormFactory $formFactory,
        ConfigInterface $wysiwygConfig,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \Cytracon\Builder\Data\Elements $builderElements,
        array $data = [],
        array $config = []
    ) {
        $this->builderElements = $builderElements;
        $data['config']['elements'] = $this->getElementList();
        $components = [];
        parent::__construct($context, $formFactory, $wysiwygConfig, $layoutFactory, $registry, $components, $data, $config);
    }

    /**
     * @param  array $elements 
     * @return array           
     */
    public function getElementList()
    {
        $list = [];
        $elements = $this->builderElements->getElements();
        if ($elements) {
            foreach ($elements as $element) {
                $list[] = $element->getData();
            }
        }
        return $list;
    }
}
