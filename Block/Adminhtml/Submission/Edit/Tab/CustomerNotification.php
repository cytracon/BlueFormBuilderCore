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

namespace Cytracon\BlueFormBuilderCore\Block\Adminhtml\Submission\Edit\Tab;

class CustomerNotification extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Cytracon\BlueFormBuilderCore\Model\Submission */
        $model = $this->_coreRegistry->registry('current_submission');
        
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('form_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => '',
                'class'  => 'fieldset-wide'
            ]
        );

        if ($model->getId()) {
            $fieldset->addField('submission_id', 'hidden', ['name' => 'submission_id']);
        }

        $fieldset->addField(
            'sender_name',
            'note',
            [
                'label' => __('Sender Name'),
                'note'  =>  $model->getCustomerSenderName() . ' '
            ]
        );

        $fieldset->addField(
            'sender_email',
            'note',
            [
                'label' => __('Sender Email'),
                'note'  =>  $model->getCustomerSenderEmail() . ' '
            ]
        );

        $fieldset->addField(
            'reply_to',
            'note',
            [
                'label' => __('Reply To'),
                'note'  =>  $model->getCustomerReplyTo() . ' '
            ]
        );

        $fieldset->addField(
            'recipients',
            'note',
            [
                'label' => __('Send Email(s) To'),
                'note'  =>  $model->getCustomerRecipients() . ' '
            ]
        );

        $fieldset->addField(
            'customer_email_subject',
            'note',
            [
                'label' => __('Email Subject'),
                'note'  =>  $model->getCustomerEmailSubject() . ' '
            ]
        );

        $fieldset->addField(
            'customer_email_body',
            'note',
            [
                'label' => __('Email Body'),
                'note'  =>  $model->getCustomerEmailBody() . ' '
            ]
        );
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Customer Notification');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Customer Notification');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
