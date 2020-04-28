UBC WP Vote
======
UBC WP Vote plugin developed based on the idea of pulsepress. Where students in UBC can perform some actions on posts and comments including rating, thumbs up and thumbs down.

Initial Setup
------
The plugin is designed to work with in WordPress multi-site network. Before having the plugin working on a site, there are few steps to setup initially.
1. Install and activate plugins including: UBC WP Vote, FacetWP, FacetWP Cache
2. Setup FacetWP
    1. Go to settings -> FacetWP -> Settings tab at the top -> Backup. Locate **export/facet-wp-export.json** file in repository and copy the Json string into **Import section** on FacetWP Settings page and click **Import**
    2. Setup FacetWP page - Create a page and set it as home page in **Settings -> Reading**. Locate **export/facet-wp-block-template-export.html** and copy block template and paste it to Gutenberg code editor of the FacetWP Home page.
3. Plugin Settings - Under Settings -> UBC WP Vote, activate post types for different rubrics. This setting can be overrided on post edit page.
