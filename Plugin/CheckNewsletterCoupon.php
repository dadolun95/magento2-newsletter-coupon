<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Plugin;

use Magento\SalesRule\Model\CouponFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Dadolun\NewsletterCoupon\Api\SubscriberInformationRepositoryInterface;
use Dadolun\NewsletterCoupon\Helper\ConfigurationHelper;

/**
 * Class CheckNewsletterCoupon
 * @package Dadolun\NewsletterCoupon\Plugin
 */
class CheckNewsletterCoupon
{

    const DEFAULT_DELAY_EXPRESSION = '+30days';

    /**
     * @var CouponFactory
     */
    protected $couponFactory;

    /**
     * @var MessageManager
     */
    protected $messageManager;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var SubscriberInformationRepositoryInterface
     */
    protected $subscriberInformationRepository;

    /**
     * @var ConfigurationHelper
     */
    protected $configurationHelper;

    /**
     * CheckNewsletterCoupon constructor.
     * @param CouponFactory $couponFactory
     * @param MessageManager $messageManager
     * @param Escaper $escaper
     * @param SubscriberInformationRepositoryInterface $subscriberInformationRepository
     * @param ConfigurationHelper $configurationHelper
     */
    public function __construct(
        CouponFactory $couponFactory,
        MessageManager $messageManager,
        Escaper $escaper,
        SubscriberInformationRepositoryInterface $subscriberInformationRepository,
        ConfigurationHelper $configurationHelper
    )
    {
        $this->couponFactory = $couponFactory;
        $this->messageManager = $messageManager;
        $this->escaper = $escaper;
        $this->subscriberInformationRepository = $subscriberInformationRepository;
        $this->configurationHelper = $configurationHelper;
    }

    /**
     * @param \Magento\Checkout\Controller\Cart\CouponPost $subject
     * @param callable $proceed
     * @return array|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundExecute(\Magento\Checkout\Controller\Cart\CouponPost $subject, callable $proceed)
    {
        $couponCode = $subject->getRequest()->getParam('remove') == 1
            ? ''
            : trim($subject->getRequest()->getParam('coupon_code'));

        $coupon = $this->couponFactory->create();
        $coupon->load($couponCode, 'code');

        if ($coupon !== null && $coupon->getCouponId()) {

            $subscriberInformation = $this->subscriberInformationRepository->getByCouponId($coupon->getCouponId());

            if ($subscriberInformation != null) {

                if ($subscriberInformation->getIsEnabled() !== "1") {
                    $this->messageManager->addErrorMessage(
                        __(
                            'The coupon "%1" can\'t be utilized: subscription is not valid or coupon was already used.',
                            $this->escaper->escapeHtml($couponCode)
                        )
                    );
                    $subject->getRequest()->setParam('coupon_code', '');
                }

                $createdAt = $subscriberInformation->getCreatedAt();
                $delayExpression = $this->configurationHelper->getDelayExpression();
                if ($delayExpression === null) {
                    $delayExpression = self::DEFAULT_DELAY_EXPRESSION;
                }
                $expirationDate = strtotime($createdAt . $delayExpression);
                $today = strtotime(date("Y-m-d"));
                if ($expirationDate < $today) {
                    $this->messageManager->addErrorMessage(
                        __(
                            'The coupon "%1" is expired.',
                            $this->escaper->escapeHtml($couponCode)
                        )
                    );
                    $subject->getRequest()->setParam('coupon_code', '');
                }
            }
        }

        return $proceed();
    }
}
