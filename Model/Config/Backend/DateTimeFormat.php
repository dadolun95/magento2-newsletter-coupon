<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Model\Config\Backend;

/**
 * Class DateTimeFormat
 * @package Dadolun\NewsletterCoupon\Model\Config\Backend
 */
class DateTimeFormat extends \Magento\Framework\App\Config\Value {

    /**
     * @return $this|\Magento\Framework\App\Config\Value
     */
    public function beforeSave()
    {
        $dataSaveAllowed = false;
        $value = $this->getValue();
        if (strtotime((string)$value) !== false) {
            $dataSaveAllowed = true;
        }
        if (!$dataSaveAllowed) {
            $value = (string)$this->getOldValue();
        }
        $this->setValue((string)$value);
        return parent::beforeSave();
    }
}
