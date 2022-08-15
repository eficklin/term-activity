# Term Activity Maps
WP core provides a current count of posts per taxonomy term; this expands that to give snapshots of post counts over the past 30, 60, 90 days

## Why?
busy sites with aggressive recirculation strategies might need a little more insight into the posts tagged with a certain taxonomy term beyond just current count

Consider:
* terms has lots of posts but they're not current, not a great candidate for recirc
* might need a certain minimum number of posts for a recirc module; grabbing everything could be too much so a quick look at the term meta could give you a date range to limit your query; in the delicate balancing acts of optimization, that might be really helpful
* data warehousing or analytics could also peek into term meta to track activity over time