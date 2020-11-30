<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Plugin;

use Dadolun\NewsletterCoupon\Helper\ConfigurationHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Dadolun\NewsletterCoupon\Api\SubscriberInformationRepositoryInterface;
use Magento\SalesRule\Api\CouponRepositoryInterface;

/**
 * Class UpdateNewsletterEmailSending
 * @package Dadolun\NewsletterCoupon\Plugin
 */
class UpdateNewsletterEmailSending
{

    const XML_PATH_SUCCESS_COUPON_EMAIL_TEMPLATE = 'newsletter/subscription/success_coupon_email_template';

    /**
     * @var ConfigurationHelper
     */
    protected $configurationHelper;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var SubscriberInformationRepositoryInterface
     */
    protected $subscriberInformationRepository;

    /**
     * @var CouponRepositoryInterface
     */
    protected $couponRepository;

    /**
     * UpdateNewsletterEmailSending constructor.
     * @param ConfigurationHelper $configurationHelper
     * @param ScopeConfigInterface $scopeConfig
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param SubscriberInformationRepositoryInterface $subscriberInformationRepository
     * @param CouponRepositoryInterface $couponRepository
     */
    public function __construct(
        ConfigurationHelper $configurationHelper,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        SubscriberInformationRepositoryInterface $subscriberInformationRepository,
        CouponRepositoryInterface $couponRepository
    )
    {
        $this->configurationHelper = $configurationHelper;
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->subscriberInformationRepository = $subscriberInformationRepository;
        $this->couponRepository = $couponRepository;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     * @return \Magento\Newsletter\Model\Subscriber
     */
    public function aroundSendConfirmationRequestEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if ($this->configurationHelper->disableConfirmationRequestEmail()) {
            return $subject;
        }
        return $proceed();
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     * @return \Magento\Newsletter\Model\Subscriber
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundSendConfirmationSuccessEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if ($this->configurationHelper->disableConfirmationSuccessEmail()) {
            return $subject;
        }

        if ($this->configurationHelper->isCouponGenerationEnabled()) {

            if ($subject->getImportMode()) {
                return $subject;
            }

            if (!$this->scopeConfig->getValue(
                    self::XML_PATH_SUCCESS_COUPON_EMAIL_TEMPLATE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ) || !$this->scopeConfig->getValue(
                    \Magento\Newsletter\Model\Subscriber::XML_PATH_SUCCESS_EMAIL_IDENTITY,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ) {
                return $subject;
            }

            /**
             * @var \Dadolun\NewsletterCoupon\Model\SubscriberInformation $subscriberInformation
             */
            $subscriberInformation = $this->subscriberInformationRepository->getBySubscriberId($subject->getSubscriberId());

            if ($subscriberInformation && $subscriberInformation->getId() && $subscriberInformation->getCouponId() !== null) {
                $coupon = $this->couponRepository->getById($subscriberInformation->getCouponId());
                $creationDate = \DateTime::createFromFormat(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT, $subscriberInformation->getCreatedAt());
                $expirationDelay = date_interval_create_from_date_string($this->configurationHelper->getDelayExpression());
                $creationDate->add($expirationDelay);
                $expirationDate = $creationDate->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

                $this->inlineTranslation->suspend();

                $this->transportBuilder->setTemplateIdentifier(
                    $this->scopeConfig->getValue(
                        self::XML_PATH_SUCCESS_COUPON_EMAIL_TEMPLATE,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                )->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId(),
                    ]
                )->setTemplateVars(
                    [
                        'subscriber' => $subject,
                        'coupon_code' => $coupon->getCode(),
                        'coupon_expiration_date' => $expirationDate
                    ]
                )->setFrom(
                    $this->scopeConfig->getValue(
                        \Magento\Newsletter\Model\Subscriber::XML_PATH_SUCCESS_EMAIL_IDENTITY,
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                )->addTo(
                    $subject->getEmail(),
                    $subject->getName()
                );
                $transport = $this->transportBuilder->getTransport();
                $transport->sendMessage();

                $this->inlineTranslation->resume();

                return $subject;

            }
        }

        return $proceed();
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param callable $proceed
     * @return \Magento\Newsletter\Model\Subscriber
     */
    public function aroundSendUnsubscriptionEmail(\Magento\Newsletter\Model\Subscriber $subject, callable $proceed)
    {
        if ($this->configurationHelper->disableUnsubscriptionEmail()) {
            return $subject;
        }
        return $proceed();
    }
}
