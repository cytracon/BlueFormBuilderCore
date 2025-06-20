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

namespace Cytracon\BlueFormBuilderCore\Controller\Submission;

use Magento\Framework\App\ResponseInterface;

class MarkAsUnread extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    /**
     * Download file action
     *
     * @return void|ResponseInterface
     */
    public function execute()
    {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $id      = $this->getRequest()->getParam('id', 0);
        $key     = $this->getRequest()->getParam('key', 0);

        if (!$id || !$key) {
            $this->messageManager->addError(__('Something went wrong while processing.'));
            return $this->_redirect($baseUrl);
        }

        $collection = $this->_objectManager->create(
            \Cytracon\BlueFormBuilderCore\Model\ResourceModel\Submission\Collection::class
        );
        
        $submission = $collection->addFieldToFilter('submission_id', $id)
        ->addFieldToFilter('submission_hash', $key)
        ->getFirstItem();

        if (!$submission->getId()) {
            $this->messageManager->addError(__('Something went wrong while processing.'));
            return $this->_redirect($baseUrl);
        }

        try {
            $submission->setRead(\Cytracon\BlueFormBuilderCore\Model\Submission::STATUS_READ);
            $submission->save();
            $this->messageManager->addSuccess(__('The submission has beend updated.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong while processing.'));
        }
        return $this->_redirect($baseUrl);
    }
}
