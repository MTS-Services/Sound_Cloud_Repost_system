<x-dashboard-summary :earnings="user()->repost_price" :dailyTotalReposts="$dailyTotalReposts" :dailyRepostMax="20" :responseRate="user()->responseRate()" :pendingRequests="$pendingRequests"
    :requestLimit="25" :credits="userCredits()" :campaigns="$totalMyCampaign" :campaignLimit="proUser() ? 10 : 2" />
