<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Plugin;

use Dadolun\NewsletterCoupon\Api\SubscriberInformationRepositoryInterface;
use Dadolun\NewsletterCoupon\Model\SubscriberInformation;
use Dadolun\NewsletterCoupon\Api\Data\SubscriberInformationInterfaceFactory;
use Magento\Newsletter\Model\Subscriber;
use Dadolun\NewsletterCoupon\Helper\ConfigurationHelper;
use Magento\SalesRule\Model\RuleRepository;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\SalesRule\Model\Rule as SalesRule;

/**
 * Class SaveSubscriberData
 * @package Dadolun\NewsletterCoupon\Plugin
 */
class SaveSubscriberData
{
    /**
     * @var SubscriberInformationRepositoryInterface
     */
    protected $subscriberInformationRepository;

    /**
     * @var SubscriberInformationInterfaceFactory
     */
    protected $subscriberInformationFactory;

    /**
     * @var ConfigurationHelper
     */
    protected $configurationHelper;

    /**
     * @var RuleRepository
     */
    protected $salesRuleRepository;

    /**
     * @var SalesRule
     */
    protected $salesRule;

    /**
     * @var CouponRepositoryInterface
     */
    protected $couponRepository;

    /**
     * SaveSubscriberData constructor.
     * @param SubscriberInformationRepositoryInterface $subscriberInformationRepository
     * @param SubscriberInformationInterfaceFactory $subscriberInformationFactory
     * @param ConfigurationHelper $configurationHelper
     * @param RuleRepository $salesRuleRepository
     * @param SalesRule $salesRule
     * @param CouponRepositoryInterface $couponRepository
     */
    public function __construct(
        SubscriberInformationRepositoryInterface $subscriberInformationRepository,
        SubscriberInformationInterfaceFactory $subscriberInformationFactory,
        ConfigurationHelper $configurationHelper,
        RuleRepository $salesRuleRepository,
        SalesRule $salesRule,
        CouponRepositoryInterface $couponRepository
    )
    {
        $this->subscriberInformationRepository = $subscriberInformationRepository;
        $this->subscriberInformationFactory = $subscriberInformationFactory;
        $this->configurationHelper = $configurationHelper;
        $this->salesRuleRepository = $salesRuleRepository;
        $this->salesRule = $salesRule;
        $this->couponRepository = $couponRepository;
    }

    /**
     * @param Subscriber $subject
     * @return array
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeAfterSave(Subscriber $subject)
    {
        if ($subject->getStatus() === Subscriber::STATUS_SUBSCRIBED) {
            $subscriberInformation = $this->subscriberInformationRepository->getBySubscriberId($subject->getSubscriberId());
            if ($subscriberInformation !== null && $subscriberInformation->getId()) {
                $subscriberInformation->setIsEnabled(true);
                $this->subscriberInformationRepository->save($subscriberInformation);
            } else {
                /**
                 * @var SubscriberInformation $subscriberInformation
                 */
                $subscriberInformation = $this->subscriberInformationFactory->create();
                $subscriberInformation->setSubscriberId($subject->getSubscriberId());
                $subscriberInformation->setSubscriberEmail($subject->getSubscriberEmail());
                $subscriberInformation->setIsEnabled(true);
                if ($this->configurationHelper->isCouponGenerationEnabled()) {
                    /**
                     * @var \Magento\SalesRule\Model\Coupon $salesRuleCoupon
                     */
                    $salesRuleCoupon = $this->generateCoupon();
                    if ($salesRuleCoupon !== false && $salesRuleCoupon->getCouponId()) {
                        $subscriberInformation->setCouponId($salesRuleCoupon->getCouponId());
                    } else {
                        throw new \Magento\Framework\Exception\LocalizedException(__('Can\'t acquire coupon, salesrule coupon type must be "Auto".'));
                    }
                }
                $this->subscriberInformationRepository->save($subscriberInformation);
            }
        }

        if ($subject->getStatus() === Subscriber::STATUS_UNSUBSCRIBED || $subject->getStatus() === Subscriber::STATUS_NOT_ACTIVE) {
            $subscriberInformation = $this->subscriberInformationRepository->getBySubscriberId($subject->getSubscriberId());
            if ($subscriberInformation !== null) {
                $subscriberInformation->setIsEnabled(false);
                $this->subscriberInformationRepository->save($subscriberInformation);
            }
        }
        return [];
    }

    /**
     * @return bool|\Magento\SalesRule\Model\Coupon
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function generateCoupon()
    {
        $coupon = false;
        /**
         * @var \Magento\SalesRule\Model\Data\Rule $usedSalesRule
         */
        $usedSalesRule = $this->salesRuleRepository->getById(intval($this->configurationHelper->getUsedSalesRuleId()));
        $couponType = $usedSalesRule->getCouponType();
        $couponUseAutoGeneration = $usedSalesRule->getUseAutoGeneration();

        if ($couponType === \Magento\SalesRule\Model\Data\Rule::COUPON_TYPE_SPECIFIC_COUPON && $couponUseAutoGeneration == 1) {
            $this->salesRule->setRuleId($usedSalesRule->getRuleId());
            /**
             * @var \Magento\SalesRule\Model\Coupon $coupon
             */
            $coupon = $this->salesRule->acquireCoupon();
        }
        return $coupon;
    }
}
