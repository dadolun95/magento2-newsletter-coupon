<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Model;

use Dadolun\NewsletterCoupon\Api\Data\SubscriberInformationInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\SalesRule\Model\CouponRepository as SalesRuleCouponRepository;
use Dadolun\NewsletterCoupon\Api\SubscriberInformationResourceInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Dadolun\NewsletterCoupon\Model\ResourceModel\SubscriberInformation as SubscriberInformationResource;
use Dadolun\NewsletterCoupon\Model\ResourceModel\SubscriberInformation\Collection as SubscriberInformationCollection;

/**
 * Class SubscriberInformation
 * @package Dadolun\NewsletterCoupon\Model
 */
class SubscriberInformation extends AbstractModel implements SubscriberInformationInterface
{

    const ENTITY = 'newsletter_subscriber_coupon';

    /**
     * @var array
     */
    protected $interfaceAttributes = [
        SubscriberInformationInterface::ID,
        SubscriberInformationInterface::SUBSCRIBER_ID,
        SubscriberInformationInterface::SUBSCRIBER_EMAIL,
        SubscriberInformationInterface::IS_ENABLED,
        SubscriberInformationInterface::COUPON_ID
    ];

    /**
     * @var SalesRuleCouponRepository
     */
    protected $couponRepository;

    /**
     * @var SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * SubscriberInformation constructor.
     * @param SalesRuleCouponRepository $couponRepository
     * @param SubscriberFactory $subscriberFactory
     * @param Context $context
     * @param Registry $registry
     * @param SubscriberInformationResource|null $resource
     * @param SubscriberInformationCollection|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        SalesRuleCouponRepository $couponRepository,
        SubscriberFactory $subscriberFactory,
        Context $context,
        Registry $registry,
        SubscriberInformationResource $resource = null,
        SubscriberInformationCollection $resourceCollection = null,
        array $data = []
    )
    {
        $this->couponRepository = $couponRepository;
        $this->subscriberFactory = $subscriberFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init(SubscriberInformationResourceInterface::class);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @return int|null
     */
    public function getSubscriberId()
    {
        return $this->getData(self::SUBSCRIBER_ID);
    }

    /**
     * @param int $subscriberId
     * @return SubscriberInformationInterface|SubscriberInformation
     */
    public function setSubscriberId($subscriberId)
    {
        return $this->setData(self::SUBSCRIBER_ID, $subscriberId);
    }

    /**
     * @return string|null
     */
    public function getSubscriberEmail()
    {
        return $this->getData(self::SUBSCRIBER_EMAIL);
    }

    /**
     * @param string $email
     * @return SubscriberInformationInterface|SubscriberInformation
     */
    public function setSubscriberEmail($email)
    {
        return $this->setData(self::SUBSCRIBER_EMAIL, $email);
    }

    /**
     * @return \Magento\Newsletter\Model\Subscriber|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSubscriber()
    {
        $subscriberEmail = $this->getSubscriberEmail();
        if ($subscriberEmail !== null) {
            $subscriber = $this->subscriberFactory->create()->loadByEmail($subscriberEmail);
        } else {
            throw new \Magento\Framework\Exception\NoSuchEntityException();
        }
        return $subscriber;
    }

    /**
     * @return boolean|null
     */
    public function getIsEnabled()
    {
        return $this->getData(self::IS_ENABLED);
    }

    /**
     * @param boolean $isSubscriptionActive
     * @return SubscriberInformationInterface|SubscriberInformation
     */
    public function setIsEnabled($isSubscriptionActive)
    {
        return $this->setData(self::IS_ENABLED, $isSubscriptionActive);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return int|null
     */
    public function getCouponId()
    {
        return $this->getData(self::COUPON_ID);
    }

    /**
     * @param int $couponId
     * @return SubscriberInformationInterface|SubscriberInformation
     */
    public function setCouponId($couponId)
    {
        return $this->setData(self::COUPON_ID, $couponId);
    }

    /**
     * @return \Magento\SalesRule\Api\Data\CouponInterface|\Magento\SalesRule\Model\Coupon|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCoupon()
    {
        $couponId = $this->getCouponId();
        if ($couponId !== null) {
            $coupon = $this->couponRepository->getById($couponId);
        } else {
            throw new \Magento\Framework\Exception\NoSuchEntityException();
        }
        return $coupon;
    }
}
