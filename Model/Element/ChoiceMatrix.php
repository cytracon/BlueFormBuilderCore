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

namespace Cytracon\BlueFormBuilderCore\Model\Element;

class ChoiceMatrix extends \Cytracon\BlueFormBuilderCore\Model\Element
{
	public function prepareValue($val)
	{
		if ($val) {
			$rows    = $this->getConfig('rows');
			$columns = $this->getConfig('columns');
			$value   = $htmlvalue = $emailValue = '';
			if ($rows && $columns) {
				foreach ($rows as $i => $row) {
					if ($row) {
						$_value = [];
						if (isset($val[$i])) {
							$tmpValue = $val[$i];
							if (!is_array($tmpValue)) {
								$tmpValue = [$tmpValue];
							} 
							foreach ($tmpValue as $_val) {
								$_vals = explode(':', $_val);
								if (isset($columns[$_vals[1]])) {
									$_value[] = $columns[$_vals[1]]['title'];
								}
							} 
						}
						$value .= $row['title'] . ': ' . implode(', ', $_value);
						if (isset($rows[$i + 1])) $value .= ' | ';
						$htmlvalue .= $row['title'] . ': ' . implode(', ', $_value) . '<br/>';
						$emailValue .= $row['title'] . ': ' . implode(', ', $_value) . '<br/>';
					}
				}
			}
			$this->setValue($value);
	        $this->setHtmlValue($htmlvalue);
	        $this->setEmailHtmlValue($emailValue);
	    }
	}
}