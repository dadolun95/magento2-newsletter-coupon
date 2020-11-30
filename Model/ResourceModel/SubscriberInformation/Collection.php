<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Model\ResourceModel\SubscriberInformation;

use Dadolun\NewsletterCoupon\Model\ResourceModel\SubscriberInformation as SubscriberInformationResource;
use Dadolun\NewsletterCoupon\Model\SubscriberInformation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Dadolun\NewsletterCoupon\Model\ResourceModel\SubscriberInformation
 */
class Collection extends AbstractCollection
{

    protected function _construct()
    {
        $this->_init(
            SubscriberInformation::class,
            SubscriberInformationResource::class
        );
    }
}
