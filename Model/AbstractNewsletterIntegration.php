<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */
namespace Dadolun\NewsletterCoupon\Model;

use Dadolun\NewsletterCoupon\Api\Data\SubscriberInformationInterface;

/**
 * Class AbstractNewsletterCouponIntegration
 * @package Dadolun\NewsletterCoupon\Model
 */
class AbstractNewsletterCouponIntegration
{
    /**
     * @param $email
     * @return bool
     */
    public function subscriberExists($email) {
        return false;
    }

    /**
     * @param SubscriberInformationInterface $subscriptionInformation
     * @param \Magento\Customer\Api\Data\CustomerInterface  $customer
     */
    protected function updateSubscriberInformations($subscriptionInformation, $customer) {}

    /**
     * @param SubscriberInformationInterface $subscriptionInformation
     * @param \Magento\Customer\Api\Data\CustomerInterface  $customer
     */
    protected function createSubscriber($subscriptionInformation, $customer) {}

    /**
     * @param SubscriberInformationInterface $subscriptionInformation
     * @param \Magento\Customer\Api\Data\CustomerInterface  $customer
     */
    public function updateSubscriber($subscriptionInformation, $customer) {
        $subscriberEmail = $subscriptionInformation->getSubscriberEmail();
        if ($this->subscriberExists($subscriberEmail)) {
            $this->updateSubscriberInformations($subscriptionInformation, $customer);
        } else {
            $this->createSubscriber($subscriptionInformation, $customer);
        }
    }

    /**
     * @param SubscriberInformationInterface $subscriptionInformation
     */
    public function deleteSubscription($subscriptionInformation) {}
}
