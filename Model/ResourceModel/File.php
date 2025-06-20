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

namespace Cytracon\BlueFormBuilderCore\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class File extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mgz_blueformbuilder_submission_file', 'file_id');
    }

    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $path = $object->getFileAbsolutePath();
        if (file_exists($path) && is_writable($path)) {
            unlink($path);
        }
        return parent::_afterSave($object);
    }
}
