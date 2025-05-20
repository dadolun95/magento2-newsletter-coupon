# Dadolun_NewsletterCoupon module for Magento 2 <img src="https://avatars.githubusercontent.com/u/168457?s=40&v=4" alt="magento" />

## ATTENTION!

This module is no longer maintained. 
NewsletterCoupon is now available as a Mage-OS module and moved [here](https://github.com/mage-os-lab/module-newsletter-coupon)


## Features
This module add features to Magento newsletter subscription
- generate a coupon for each subscription (must be a "SPECIFIC" coupon type salesrule with "Use Auto Generation" enabled)
- send coupon informations to the subscribed user (extending magento newsletter email template)
- controls each coupon expiration. You can change default configuration at ___Store > Configuration > Dadolun > Newsletter Coupon > Coupon Expiration Expression___ config path.
- adds graft for integrations with email marketing platforms

## Installation
You can install this module adding it on app/code folder or with composer.
##### COMPOSER
You need to require the package throught composer:
```
composer require dadolun95/magento2-newsletter-coupon
```
Then you'll need to enable the module and update your database:
```
php bin/magento module:enable Dadolun_NewsletterCoupon
php bin/magento setup:upgrade
```
##### SOURCE CODE
If you choose to add the module from source code instead of using composer you need to add module's files on your app/code folder.
Then enable it and update the database:
```
php bin/magento module:enable Dadolun_NewsletterCoupon
php bin/magento setup:upgrade
```
##### CONFIGURATION
This module comes with standard functionality disabled. You'll need to enable it from configurations on ___Store > Configuration > Dadolun > Newsletter Coupon > Enable Coupon Generation___ and connect a valid salesrule. 
You can set the expiration delay time expression you prefer (https://www.php.net/manual/en/datetime.formats.relative.php) on ___Store > Configuration > Dadolun > Newsletter Coupon > Coupon Expiration Delay___ path.
This will allow you to generate and link coupon on each new user newsletter subscription.
So create a new SalesRule from your admin panel on __Marketing > Promotions > Cart Price Rules__ calling it "Newsletter Subscription promo" or something like this.
Remember that this salesrule must have "SPECIFIC" coupon type and "Use Auto Generation" checkbox must be flagged. Expiration coupon control comes from the module for each coupon adding regular expression setted on each coupon creation date.
Set the other rule's settings as you like.
Set the new rule id on ___Store > Configuration > Dadolun > Newsletter Coupon > Used Sales Rule___.

## Integrations
You can create, delete or update subscriber informations on external platforms with a new module linked with Dadolun_NewsletterCoupon.
Requirements:
- if you've installed a 3rd party module that send newsletter email remember to disable magento's from ___Store > Configuration > Customers > Newsletter > Subscription Options > Disable Newsletter * Sending___ config path.
- extend __Dadolun\NewsletterCoupon\Model\AbstractNewsletterIntegration__ class on your module adding logic on methods.
- inject your new __Vendor_Module\Model\MyClassName__ with a di argument preference like this:
```
<type name="Dadolun\NewsletterCoupon\Api\SubscriberInformationRepositoryInterface">
    <arguments>
        <argument name="marketingEmailIntergrations" xsi:type="array">
            <item name="your_integration_name" xsi:type="object">Vendor\Module\Model\MyClassName</item>
        </argument>
    </arguments>
</type>
```
- That's all, your module is now able to talk with your favorite email marketing platform

## Contributing
Contributions are very welcome. In order to contribute, please fork this repository and submit a [pull request](https://docs.github.com/en/free-pro-team@latest/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request).
