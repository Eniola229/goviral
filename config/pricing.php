<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Markup Percentage (FALLBACK)
    |--------------------------------------------------------------------------
    |
    | This is your safety net - applies when no specific rule matches
    | Set to 15% as requested for uncaught services
    |
    */
    'default_markup' => 15, // 15% fallback for unknown services

    /*
    |--------------------------------------------------------------------------
    | Service Type Markup (by keyword in service name)
    |--------------------------------------------------------------------------
    |
    | Competitive markups based on typical SMM panel margins
    | Priority 3 (checked after combined and platform markups)
    |
    */
    'service_type_markup' => [
        'followers' => 35,      // 35% - High demand, good margins
        'likes' => 25,          // 25% - Very common, keep competitive
        'comments' => 30,       // 30% - Medium demand
        'shares' => 25,         // 25% - Similar to likes
        'views' => 20,          // 20% - High volume, stay competitive
        'subscribers' => 35,    // 35% - Similar to followers
        'retweets' => 25,       // 25% - Twitter specific
        'saves' => 25,          // 25% - Instagram/TikTok
        'reach' => 20,          // 20% - Impressions/reach services
        'impressions' => 20,    // 20% - Similar to reach
        'story' => 25,          // 25% - Story views
        'live' => 30,           // 30% - Live stream services
        'plays' => 20,          // 20% - Music/video plays
        'downloads' => 25,      // 25% - App downloads
        'members' => 30,        // 30% - Group/channel members
        'reactions' => 25,      // 25% - Facebook reactions
        'engagement' => 30,     // 30% - General engagement packages
        'verified' => 40,       // 40% - Premium verification services
        'premium' => 35,        // 35% - Premium tier services
    ],

    /*
    |--------------------------------------------------------------------------
    | Platform-Specific Markup (Social Media)
    |--------------------------------------------------------------------------
    |
    | Platform-based pricing strategy
    | Priority 2 (checked after combined markup)
    |
    */
    'platform_markup' => [
        // High-value platforms
        'twitter' => 35,        // 35% - Good margins
        'x' => 35,              // 35% - Same as Twitter
        'tiktok' => 35,         // 35% - Trending platform
        'youtube' => 30,        // 30% - Competitive market
        'instagram' => 30,      // 30% - Most popular
        'spotify' => 35,        // 35% - Music streaming
        'soundcloud' => 30,     // 30% - Music platform
        
        // Medium-value platforms
        'facebook' => 25,       // 25% - Large volume
        'linkedin' => 30,       // 30% - Professional network
        'snapchat' => 28,       // 28% - Younger demographic
        'pinterest' => 25,      // 25% - Visual platform
        'twitch' => 30,         // 30% - Live streaming
        'discord' => 25,        // 25% - Community platform
        'reddit' => 25,         // 25% - Forum style
        'tumblr' => 22,         // 22% - Niche platform
        
        // Lower-volume but stable
        'telegram' => 20,       // 20% - Messaging app
        'whatsapp' => 18,       // 18% - Personal messaging
        'clubhouse' => 25,      // 25% - Audio social
        'vimeo' => 25,          // 25% - Video platform
        'dailymotion' => 22,    // 22% - Video platform
        'kwai' => 25,           // 25% - Short video
        'likee' => 25,          // 25% - Short video
        'triller' => 25,        // 25% - Short video
        
        // African/Regional platforms (may be on Ogaviral)
        'nairaland' => 20,      // 20% - Nigerian forum
        'africantrends' => 20,  // 20% - African content
    ],

    /*
    |--------------------------------------------------------------------------
    | Combined Markup (Platform + Service Type)
    |--------------------------------------------------------------------------
    |
    | For specific high-value combinations
    | Priority 1 (HIGHEST - checked first)
    |
    */
    'combined_markup' => [
        // Instagram combinations
        'instagram_followers' => 35,        // Popular combo
        'instagram_likes' => 28,            // Very common
        'instagram_views' => 25,            // Story/reel views
        'instagram_comments' => 32,         // Higher value
        'instagram_saves' => 30,            // Engagement metric
        
        // TikTok combinations
        'tiktok_followers' => 38,           // High demand
        'tiktok_likes' => 30,               // Very popular
        'tiktok_views' => 25,               // High volume
        'tiktok_shares' => 28,              // Engagement
        'tiktok_comments' => 32,            // Quality metric
        
        // YouTube combinations
        'youtube_subscribers' => 35,        // Valuable
        'youtube_views' => 25,              // High volume
        'youtube_likes' => 28,              // Engagement
        'youtube_comments' => 32,           // Quality signal
        'youtube_live' => 35,               // Live stream viewers
        
        // Twitter/X combinations
        'twitter_followers' => 38,          // High value
        'twitter_retweets' => 30,           // Viral potential
        'twitter_likes' => 28,              // Common
        'x_followers' => 38,                // Same as Twitter
        'x_retweets' => 30,                 // Same as Twitter
        
        // Facebook combinations
        'facebook_followers' => 28,         // Page followers
        'facebook_likes' => 25,             // Post likes
        'facebook_shares' => 28,            // Viral potential
        'facebook_views' => 22,             // Video views
        
        // Spotify combinations
        'spotify_plays' => 32,              // Music plays
        'spotify_followers' => 35,          // Artist followers
        'spotify_saves' => 33,              // Playlist saves
        
        // Telegram combinations
        'telegram_members' => 25,           // Channel members
        'telegram_views' => 20,             // Post views
        
        // LinkedIn combinations
        'linkedin_followers' => 35,         // Professional network
        'linkedin_connections' => 35,       // Personal connections
        'linkedin_likes' => 30,             // Post engagement
    ],

    /*
    |--------------------------------------------------------------------------
    | Minimum Markup
    |--------------------------------------------------------------------------
    |
    | Never go below this percentage to maintain profitability
    | Set conservatively to ensure margins
    |
    */
    'minimum_markup' => 15, // Matches your fallback requirement

    /*
    |--------------------------------------------------------------------------
    | Maximum Markup
    |--------------------------------------------------------------------------
    |
    | Cap to prevent excessive pricing that could lose customers
    | 60% is aggressive but reasonable for SMM reselling
    |
    */
    'maximum_markup' => 60, // Reduced from 100% to stay competitive

    /*
    |--------------------------------------------------------------------------
    | Round Prices
    |--------------------------------------------------------------------------
    |
    | Whether to round final prices to nearest whole number
    | TRUE for cleaner pricing display
    |
    */
    'round_prices' => true,

    /*
    |--------------------------------------------------------------------------
    | Markup Mode
    |--------------------------------------------------------------------------
    |
    | 'percentage' - Add percentage to original price (recommended)
    | 'multiplier' - Multiply original price by value
    |
    */
    'markup_mode' => 'percentage',

    /*
    |--------------------------------------------------------------------------
    | Volume-Based Adjustments (Optional - for future use)
    |--------------------------------------------------------------------------
    |
    | You can implement volume-based pricing where higher quantities
    | get slightly lower markups to encourage bulk orders
    |
    */
    'volume_adjustments' => [
        'enabled' => false,
        'tiers' => [
            // Example: reduce markup for bulk orders
            1000 => -2,     // -2% for orders >= 1000
            5000 => -5,     // -5% for orders >= 5000
            10000 => -8,    // -8% for orders >= 10000
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Conversion Buffer (Optional)
    |--------------------------------------------------------------------------
    |
    | If you're dealing with currency conversion, add a small buffer
    | to account for exchange rate fluctuations
    |
    */
    'currency_buffer' => 2, // Add 2% for currency fluctuation protection
];