<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PricingService
{
    /**
     * Calculate marked up price for a service
     *
     * @param float $originalPrice Original price from API
     * @param string $serviceName Full service name
     * @param int|null $quantity Optional quantity for volume adjustments
     * @return float Marked up price
     */
    public static function calculatePrice($originalPrice, $serviceName, $quantity = null)
    {
        // Validate input
        if (!is_numeric($originalPrice) || $originalPrice <= 0) {
            Log::warning('Invalid original price provided', [
                'price' => $originalPrice,
                'service' => $serviceName
            ]);
            return 0;
        }

        $markup = self::getMarkupPercentage($serviceName);
        
        // Apply volume-based adjustments if enabled and quantity provided
        if ($quantity && config('pricing.volume_adjustments.enabled', false)) {
            $markup = self::applyVolumeAdjustment($markup, $quantity);
        }
        
        // Calculate marked up price
        $markedUpPrice = $originalPrice * (1 + ($markup / 100));
        
        // Apply minimum and maximum limits
        $minMarkup = config('pricing.minimum_markup', 15);
        $maxMarkup = config('pricing.maximum_markup', 60);
        
        $minPrice = $originalPrice * (1 + ($minMarkup / 100));
        $maxPrice = $originalPrice * (1 + ($maxMarkup / 100));
        
        $markedUpPrice = max($minPrice, min($maxPrice, $markedUpPrice));
        
        // Apply currency buffer if configured
        $currencyBuffer = config('pricing.currency_buffer', 0);
        if ($currencyBuffer > 0) {
            $markedUpPrice = $markedUpPrice * (1 + ($currencyBuffer / 100));
        }
        
        // Round if configured
        if (config('pricing.round_prices', true)) {
            // For very small prices, round to 2 decimals instead of whole numbers
            if ($markedUpPrice < 1) {
                $markedUpPrice = round($markedUpPrice, 2);
            } else {
                $markedUpPrice = round($markedUpPrice);
            }
        }
        
        return $markedUpPrice;
    }

    /**
     * Get markup percentage for a service
     *
     * @param string $serviceName Full service name
     * @return float Markup percentage
     */
    public static function getMarkupPercentage($serviceName)
    {
        $serviceName = strtolower($serviceName);
        
        // Detect platform
        $platform = self::detectPlatform($serviceName);
        
        // Detect service type
        $serviceType = self::detectServiceType($serviceName);
        
        // Priority 1: Check for combined markup (platform + service type)
        if ($platform && $serviceType) {
            $combinedKey = $platform . '_' . $serviceType;
            $combinedMarkup = config('pricing.combined_markup.' . $combinedKey);
            
            if ($combinedMarkup !== null) {
                return $combinedMarkup;
            }
        }
        
        // Priority 2: Check for platform-specific markup
        if ($platform) {
            $platformMarkup = config('pricing.platform_markup.' . $platform);
            
            if ($platformMarkup !== null) {
                return $platformMarkup;
            }
        }
        
        // Priority 3: Check for service type markup
        if ($serviceType) {
            $serviceTypeMarkup = config('pricing.service_type_markup.' . $serviceType);
            
            if ($serviceTypeMarkup !== null) {
                return $serviceTypeMarkup;
            }
        }
        
        // Priority 4: Use default markup (15% as fallback for unknown services)
        $defaultMarkup = config('pricing.default_markup', 15);
        
        // Log when using fallback markup for monitoring
        // Log::info('Using fallback markup for service', [
        //     'service_name' => $serviceName,
        //     'markup' => $defaultMarkup,
        //     'platform' => $platform,
        //     'service_type' => $serviceType
        // ]);
        
        return $defaultMarkup;
    }

    /**
     * Apply volume-based adjustment to markup
     *
     * @param float $markup Base markup percentage
     * @param int $quantity Order quantity
     * @return float Adjusted markup percentage
     */
    protected static function applyVolumeAdjustment($markup, $quantity)
    {
        $tiers = config('pricing.volume_adjustments.tiers', []);
        
        // Sort tiers in descending order
        krsort($tiers);
        
        foreach ($tiers as $threshold => $adjustment) {
            if ($quantity >= $threshold) {
                $adjustedMarkup = $markup + $adjustment;
                // Ensure we don't go below minimum
                $minMarkup = config('pricing.minimum_markup', 15);
                return max($minMarkup, $adjustedMarkup);
            }
        }
        
        return $markup;
    }

    /**
     * Detect platform from service name
     *
     * @param string $serviceName
     * @return string|null
     */
    protected static function detectPlatform($serviceName)
    {
        $platforms = array_keys(config('pricing.platform_markup', []));
        
        // Sort by length (descending) to match longer names first
        // This prevents "facebook" from matching before "facebook messenger"
        usort($platforms, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        foreach ($platforms as $platform) {
            if (stripos($serviceName, $platform) !== false) {
                return $platform;
            }
        }
        
        // Check for alternative/common names
        $alternativeNames = [
            'twitter' => ['tweet', 'twt'],
            'x' => ['twitter.com/x'],
            'instagram' => ['insta', 'ig'],
            'facebook' => ['fb'],
            'youtube' => ['yt'],
            'tiktok' => ['tik tok'],
            'whatsapp' => ['wa'],
            'telegram' => ['tg'],
        ];
        
        foreach ($alternativeNames as $platform => $alternatives) {
            foreach ($alternatives as $alt) {
                if (stripos($serviceName, $alt) !== false) {
                    return $platform;
                }
            }
        }
        
        return null;
    }

    /**
     * Detect service type from service name
     *
     * @param string $serviceName
     * @return string|null
     */
    protected static function detectServiceType($serviceName)
    {
        $serviceTypes = array_keys(config('pricing.service_type_markup', []));
        
        // Sort by length (descending) to match longer phrases first
        usort($serviceTypes, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        foreach ($serviceTypes as $serviceType) {
            if (stripos($serviceName, $serviceType) !== false) {
                return $serviceType;
            }
        }
        
        // Check for plural/singular variations and common alternatives
        $variations = [
            'followers' => ['follower', 'follow', 'fans', 'fan'],
            'likes' => ['like', 'love', 'hearts', 'heart'],
            'comments' => ['comment', 'reply', 'replies'],
            'shares' => ['share', 'repost', 'reposts'],
            'views' => ['view', 'watch', 'watching'],
            'subscribers' => ['subscriber', 'subscribe', 'subscription', 'subs', 'sub'],
            'retweets' => ['retweet', 'rt'],
            'saves' => ['save', 'saved', 'bookmark', 'bookmarks'],
            'members' => ['member', 'join', 'joins'],
            'plays' => ['play', 'stream', 'streams'],
            'impressions' => ['impression', 'reach'],
            'engagement' => ['engage', 'interaction', 'interactions'],
        ];
        
        foreach ($variations as $serviceType => $alternatives) {
            foreach ($alternatives as $alt) {
                if (stripos($serviceName, $alt) !== false) {
                    return $serviceType;
                }
            }
        }
        
        return null;
    }

    /**
     * Get detailed pricing breakdown for display
     *
     * @param float $originalPrice
     * @param string $serviceName
     * @param int|null $quantity
     * @return array
     */
    public static function getPricingBreakdown($originalPrice, $serviceName, $quantity = null)
    {
        $markup = self::getMarkupPercentage($serviceName);
        $finalPrice = self::calculatePrice($originalPrice, $serviceName, $quantity);
        $markupAmount = $finalPrice - $originalPrice;
        $actualMarkupPercentage = $originalPrice > 0 ? (($finalPrice - $originalPrice) / $originalPrice) * 100 : 0;
        
        return [
            'original_price' => round($originalPrice, 2),
            'base_markup_percentage' => $markup,
            'actual_markup_percentage' => round($actualMarkupPercentage, 2),
            'markup_amount' => round($markupAmount, 2),
            'final_price' => $finalPrice,
            'platform' => self::detectPlatform(strtolower($serviceName)),
            'service_type' => self::detectServiceType(strtolower($serviceName)),
            'quantity' => $quantity,
            'using_fallback' => !self::detectPlatform(strtolower($serviceName)) && !self::detectServiceType(strtolower($serviceName)),
        ];
    }

    /**
     * Batch calculate prices for multiple services
     *
     * @param array $services Array of services with 'rate' and 'name' keys
     * @return array Services with added 'marked_up_price' key
     */
    public static function batchCalculatePrices(array $services)
    {
        $processedServices = [];
        
        foreach ($services as $service) {
            $originalPrice = $service['rate'] ?? 0;
            $serviceName = $service['name'] ?? 'Unknown Service';
            
            $processedService = $service;
            $processedService['original_price'] = $originalPrice;
            $processedService['marked_up_price'] = self::calculatePrice($originalPrice, $serviceName);
            $processedService['markup_percentage'] = self::getMarkupPercentage($serviceName);
            
            $processedServices[] = $processedService;
        }
        
        return $processedServices;
    }

    /**
     * Calculate profit margin for a service
     *
     * @param float $originalPrice
     * @param string $serviceName
     * @return array
     */
    public static function calculateProfitMargin($originalPrice, $serviceName)
    {
        $finalPrice = self::calculatePrice($originalPrice, $serviceName);
        $profit = $finalPrice - $originalPrice;
        $profitMargin = $originalPrice > 0 ? ($profit / $finalPrice) * 100 : 0;
        
        return [
            'original_price' => $originalPrice,
            'final_price' => $finalPrice,
            'profit_amount' => $profit,
            'profit_margin' => round($profitMargin, 2), // Percentage of final price that is profit
            'markup_percentage' => round(($profit / $originalPrice) * 100, 2), // Percentage added to original
        ];
    }

    /**
     * Calculate actual profit from an order
     * This reverses the markup to find the original cost
     *
     * @param float $customerCharge The amount customer paid
     * @param int $quantity Order quantity
     * @param string $serviceName Service name for markup detection
     * @return float Profit amount
     */
    public static function calculateProfit($customerCharge, $quantity, $serviceName)
    {
        if ($customerCharge <= 0) {
            return 0;
        }

        // Get the markup percentage that was used
        $markupPercentage = self::getMarkupPercentage($serviceName);
        
        // Apply minimum/maximum limits
        $minMarkup = config('pricing.minimum_markup', 15);
        $maxMarkup = config('pricing.maximum_markup', 60);
        $markupPercentage = max($minMarkup, min($maxMarkup, $markupPercentage));
        
        // Reverse calculate the original cost from customer charge
        // Formula: customerCharge = originalCost * (1 + markup/100)
        // Therefore: originalCost = customerCharge / (1 + markup/100)
        $originalCost = $customerCharge / (1 + ($markupPercentage / 100));
        
        // Profit is the difference
        $profit = $customerCharge - $originalCost;
        
        return round($profit, 2);
    }

    /**
     * Get detailed profit breakdown for an order
     *
     * @param float $customerCharge
     * @param int $quantity
     * @param string $serviceName
     * @return array
     */
    public static function getProfitBreakdown($customerCharge, $quantity, $serviceName)
    {
        $profit = self::calculateProfit($customerCharge, $quantity, $serviceName);
        $originalCost = $customerCharge - $profit;
        $profitMargin = $customerCharge > 0 ? ($profit / $customerCharge) * 100 : 0;
        
        return [
            'customer_charge' => round($customerCharge, 2),
            'original_cost' => round($originalCost, 2),
            'profit_amount' => round($profit, 2),
            'profit_margin' => round($profitMargin, 2),
            'markup_percentage' => self::getMarkupPercentage($serviceName),
            'quantity' => $quantity,
        ];
    }
}