<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Api;

/**
 * Interface SubscriberCouponRepositoryInterface
 * @package Dadolun\NewsletterCoupon\Api
 */
interface SubscriberInformationRepositoryInterface
{
    /**
     * @param \Dadolun\NewsletterCoupon\Model\SubscriberInformation $subscriberInformation
     * @return mixed
     */
    public function save(\Dadolun\NewsletterCoupon\Model\SubscriberInformation $subscriberInformation);

    /**
     * @param int $subscriberInformationId
     * @return \Dadolun\NewsletterCoupon\Model\SubscriberInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($subscriberInformationId);

    /**
     * @param $subscriberId
     * @return \Dadolun\NewsletterCoupon\Model\SubscriberInformation|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBySubscriberId($subscriberId);

    /**
     * @param $couponId
     * @return \Dadolun\NewsletterCoupon\Model\SubscriberInformation|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCouponId($couponId);

    /**
     * @param \Dadolun\NewsletterCoupon\Model\SubscriberInformation $subscriberInformation
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Dadolun\NewsletterCoupon\Model\SubscriberInformation $subscriberInformation);

    /**
     * @param int $orderItemInformationId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($orderItemInformationId);
}
