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

namespace Cytracon\BlueFormBuilderCore\Controller\Adminhtml\Form;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Cytracon_BlueFormBuilderCore::form_save';

    /**
     * @var array
     */
    protected $arrayFields = ['disable_multiple_fields', 'conditional', 'mailchimp_merge_fields', 'mailchimp_groups', 'mailchimp_conditions'];

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Cytracon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \Cytracon\BlueFormBuilderCore\Helper\Form
     */
    protected $formHelper;

    /**
     * @param \Magento\Backend\App\Action\Context                   $context       
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor 
     * @param \Cytracon\Core\Helper\Data                             $coreHelper    
     * @param \Cytracon\BlueFormBuilderCore\Helper\Form                     $formHelper    
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Cytracon\Core\Helper\Data $coreHelper,
        \Cytracon\BlueFormBuilderCore\Helper\Form $formHelper
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->coreHelper    = $coreHelper;
        $this->formHelper    = $formHelper;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (empty($data['form_id'])) {
            unset($data['form_id']);
        }
        if ($data) {
            foreach ($this->arrayFields as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = $this->coreHelper->serialize($data[$field]);
                }
            }

            /** @var \Cytracon\BlueFormBuilderCore\Model\Form $model */
            $model = $this->_objectManager->create(\Cytracon\BlueFormBuilderCore\Model\Form::class);
            $id    = $this->getRequest()->getParam('form_id');

            try {
                $model->load($id);
                if ($id && !$model->getId()) {
                    throw new LocalizedException(__('This form no longer exists.'));
                }
                $model->setData($data);
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the form.'));
                $this->dataPersistor->clear('current_form');

                $this->_eventManager->dispatch(
                    'bfb_form_after_save',
                    [
                        'form'    => $model,
                        'request' => $this->getRequest()
                    ]
                );

                if ($redirectBack === 'save_and_new') {
                    return $resultRedirect->setPath('*/*/new');
                }

                if ($redirectBack === 'save_and_duplicate') {
                    $duplicate = $this->formHelper->duplicateForm($model);
                    $this->messageManager->addSuccessMessage(__('You duplicated the form'));
                    return $resultRedirect->setPath('*/*/edit', ['form_id' => $duplicate->getId(), '_current' => true]);
                }

                if ($redirectBack === 'save_and_close') {
                    return $resultRedirect->setPath('*/*/*');
                }

                return $resultRedirect->setPath('*/*/edit', ['form_id' => $model->getId(), '_current' => true]);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the form.'));
            }

            $this->dataPersistor->set('current_form', $data);
            return $resultRedirect->setPath('*/*/edit', ['form_id' => $this->getRequest()->getParam('form_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
