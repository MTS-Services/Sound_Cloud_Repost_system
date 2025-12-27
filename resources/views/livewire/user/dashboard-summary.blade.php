
<x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="$dailyTotalReposts" :dailyRepostMax="20" :responseRate="user()->responseRate()" :pendingRequests="$pendingRequests"
    :requestLimit="proUser() ? 100 : 20" :credits="userCredits()" :campaigns="$totalMyCampaign" :campaignLimit="proUser() ? 10 : 2" />
