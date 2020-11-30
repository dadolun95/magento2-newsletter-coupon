<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Api;

use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;

/**
 * Interface SubscriberInformationResourceInterface
 * @package Dadolun\NewsletterCoupon\Api
 */
interface SubscriberInformationResourceInterface
{
    /**
     * @param AbstractModel $object
     * @return mixed
     */
    public function save(AbstractModel $object);

    /**
     * @param AbstractModel $object
     * @param $value
     * @param null $field
     * @return mixed
     */
    public function load(AbstractModel $object, $value, $field = null);

    /**
     * @param AbstractModel $object
     * @return mixed
     */
    public function delete(AbstractModel $object);
}
