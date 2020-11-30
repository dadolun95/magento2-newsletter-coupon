<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Model\ResourceModel;

use Dadolun\NewsletterCoupon\Api\SubscriberInformationResourceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class SubscriberInformation
 * @package Dadolun\NewsletterCoupon\Model\ResourceModel
 */
class SubscriberInformation extends AbstractDb implements SubscriberInformationResourceInterface
{

    protected function _construct()
    {
        $this->_init('newsletter_subscriber_coupon', 'entity_id');
    }
}
