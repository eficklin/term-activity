# Term Activity Maps
WP core happily provides a current count of posts per taxonomy term. This plugin builds on that by calculating a snapshot of post counts over the past 3, 30, and 90 day periods and storing it as a serialized array in term meta.

```
array (
  'generated_at' => 1661192822,
  'periods' => 
  array (
    0 => 
    array (
      'days_prior' => 3,
      'count' => 11,
    ),
    1 => 
    array (
      'days_prior' => 30,
      'count' => 25,
    ),
    2 => 
    array (
      'days_prior' => 90,
      'count' => 44,
    ),
  ),
)
```

Publishing, unpublishing, or changing term relationships will trigger an update of the term's activity snapshot. A helper class provides some utility functions for getting and displaying the activity maps.

This plugin is a code sample, purposely bare-bones to demonstrate a bit of coding style and thought process. If you find the ideas here useful, by all means, copy away! However, this is not production ready code. I won't say "use at your own risk" because you shouldn't even use it.

## Why?
A busy site with an aggressive recirculation strategies might need a little more insight into the posts tagged with a certain taxonomy term beyond what the core-provided counts can offer.

Consider:
* a term has lots of posts but they're not current, perhaps related to an election or a sporting event; likely not a great candidate for recirculation but a simple count of the posts won't tell you that
* an editor might need a certain minimum number of posts for a recirculation module (a listicle, maybe?); when it comes to the underlying implementation, grabbing everything could be too much, but how do you code some limits to the query? a quick look at the snapshot in term meta could give you a date range to limit your query; in the delicate balancing acts of optimization, that might be really helpful
* as a reporting aid, external tools/services (or another plugin) could be given a way to peek into term meta to track activity over time

I concocted this sample from work developed for a client that had quite a few very active newsrooms publishing content on a WP multisite. Their editorial teams made heavy use of the core "Category" taxonomy to organize content and the revenue team was always eager to boost recirculation (and ad $$$). Our work included a suite of article curation tools to support these recirculation strategies. Central to the output was a controller class that executed these curation queries.It benefited from the extra metadata by being able to add reliable date limits--one of the best WP database optimizations there is. In this context, the boost to the controller queries outweighed the extra load in computing the maps when articles were published. An important contextual note: we arrived at this solution at the behest of the platform engineers who were, at the time, urging us to remove or otherwise minimize the amount of term queries. Though not included here, those maps also contained post IDs alongside counts. This allowed for fallback strategies that minimized, as requested, expensive term queries, replacing them with simpler post queries with either a data range or explicit list of post IDs (both more efficient alternatives).