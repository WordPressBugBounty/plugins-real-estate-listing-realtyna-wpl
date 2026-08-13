=== Realtyna Organic IDX plugin + WPL Real Estate ===
Contributors: realtyna
Donate link: https://realtyna.com/
Tags: RESO Web API, IDX, MLS, Real Estate, Realty
Requires at least: 5.3
Tested up to: 7.0
Stable tag: 5.4.1
Requires PHP: 7.4
Version: 5.4.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/license-list.html#GPLv2

Your comprehensive solution for creating dynamic and feature-rich real estate websites on WordPress. Designed to cater to the diverse needs of real estate professionals, WPL offers unparalleled flexibility and scalability, empowering you to manage property listings, integrate MLS data, and enhance user engagement seamlessly.

== Key Features ==
*   Flexible Property Listings: Effortlessly add, modify, and showcase property listings with customizable fields tailored to your specific requirements.
*   Advanced Search Functionality: Provide users with intuitive search options, enabling them to filter properties based on various criteria for a personalized browsing experience.
*   MLS Integration with MLS On The Fly™: Stay ahead with MLS On The Fly™, Realtyna’s latest technology that revolutionizes MLS integration. Starting April 1, 2025, WPL Basic is fully compatible with MLS On The Fly™, allowing you to display real-time MLS listings directly on your website without the need for extensive data storage. This integration offers lightning-fast performance, reduces hosting burdens, and ensures your listings are always up-to-date.
Supported MLS Providers: [https://realtyna.com/mls-providers](https://realtyna.com/mls-providers)
MLS On The Fly: [https://realtyna.com/mls-on-the-fly](https://realtyna.com/mls-on-the-fly)
Docs: [https://docs.realtyfeed.com](https://docs.realtyfeed.com)
*   Scalable Add-ons: Enhance your website’s functionality with a range of add-ons, including demographic packages, Listhub integration, and native mobile apps, ensuring your platform evolves alongside your business needs.
*   Theme Compatibility: WPL seamlessly integrates with various themes, offering both native and compatible options to maintain your site’s aesthetic appeal while delivering robust functionality.

== Why Choose Realtyna WPL Real Estate? ==
By integrating WPL with MLS On The Fly™, you position your website at the forefront of real estate technology. Experience reduced hosting costs, faster load times, and real-time data synchronization, providing your clients with an unparalleled property search experience.
Elevate your real estate website with the Realtyna WPL Real Estate plugin and harness the power of MLS On The Fly™ for a streamlined, efficient, and cutting-edge property listing platform.


= How can I ask about custom developments? =

Feel free to contact us: [https://support.realtyna.com/index.php?/Default/Tickets/Submit](https://support.realtyna.com/index.php?/Default/Tickets/Submit)

= How can I report issues? =

Submit a support ticket on Realtyna ticketing system: [https://support.realtyna.com/index.php?/Default/Tickets/Submit](https://support.realtyna.com/index.php?/Default/Tickets/Submit)

== Screenshots ==

1. Add/Edit listing
2. Property Data Structure
3. Agent Profile
4. List view
5. Map view
6. Property Details
7. Agent listing

== Changelog ==
= 5.4.1 =
- Security: The permission check behind the agent-level admin endpoints did not enforce the role it named, leaving those endpoints open to any visitor
- Security: The inline field editors on the listing and user wizards accepted any database table and column from the request, and are now limited to the fields those forms render
- Security: Listing images, attachments, videos, rooms and inline fields can now only be changed by users who have access to that listing
- Security: Reassigning, cloning and setting additional agents on a listing now check access to that specific listing
- Security: WPL admin pages are registered with WordPress capabilities instead of role names, so the pages meant for administrators are limited to them
- Security: The listing manager sort and paging parameters are now bound before they reach the query
- Security: Uploads are restricted to an allowlist of media types, replacing a blocklist that missed several executable extensions
- Security: Uploaded file names are sanitised, so a crafted name can no longer be written outside the item folder
- Security: Membership renewal, expiry and agent reassignment are restricted to administrators
- Security: The access level lookup is now a bound query
- Security: PRO Add-on: the public agents and users REST endpoints bind their filter parameters, which were previously placed into the query as given
- Security: PRO Add-on: the multi-agents REST endpoint no longer accepts a listing id from the query string in place of the one in the route
- Security: PRO Add-on: the Zapier trigger endpoints escape their filter parameters
- Security: PRO Add-on: payment endpoints confirm a transaction belongs to the current user, and a transaction can no longer be raised against another user's account
- Security: PRO Add-on: the Stripe webhook and password reset queries are bound
- Fixed: The map was missing for some visitors, most often on Android, because the crawler check compared the visitor's browser against search engine names such as "Google" instead of the crawler user agents themselves
- Improved: Upload fields fall back to the image and video defaults when their configured extension list is empty or unusable

= 5.4.0 =
- Security: Restricted the IDX AJAX endpoints to administrators and limited dispatch to a known list of functions
- Security: Required the site's IDX token before the IDX REST API import and update routes run
- Security: Listing images downloaded during an IDX import are now stored under an extension derived from the verified image content
- Security: Added nonce verification to the Organic IDX wizard AJAX endpoints to block cross-site request forgery
- Security: Restricted the IDX payment status check to administrators
- Security: Escaped the min/max number search field, which reflected a URL parameter into the page unescaped
- Security: IDX listing images are now downloaded through wp_safe_remote_get so a crafted URL cannot reach the local network
- Security: Removed the benchmarker self-update, which downloaded and executed PHP from a remote server
- Security: The reCAPTCHA site key is no longer passed through a translation function
- Security: Public profile visibility now honors the configured access level for administrators
- Security: Escaped output across admin screens, search widgets, map scripts and listing templates
- Fixed: MySQL 8 compatibility for membership expiry, listing expiry notices and custom date fields, which failed with "Incorrect DATETIME value" on strict servers
- Fixed: Map clusters never split into individual markers however far you zoomed in
- Fixed: Stale cluster icons left behind after the map reloaded, showing the same listings twice
- Fixed: Google Maps drawing library is only requested when the APS addon needs it, and pinned to a version that still provides it, resolving the DrawingManager console error
- Fixed: Location text search ignored single character numeric terms such as street numbers
- Fixed: Area search min/max fields showed the configured range as if it were an entered value
- Fixed: Duplicate robots meta output on property pages when Yoast SEO or Rank Math is active
- Fixed: First language tab is now visible by default on multi-language textarea fields
- Improved: Replaced utf8_decode/utf8_encode, which are removed in current PHP versions
- Improved: Chart.js and imagesLoaded now load from the bundled copy and WordPress core instead of a CDN
- Improved: Outgoing requests and geocoding now use the WordPress HTTP API
- Improved: Migration and benchmarker files are written to the uploads folder, so they survive plugin updates
- Improved: Removed bundled copies of libraries already included in WordPress
- Improved: Correct plural forms for rooms, bedrooms and bathrooms, and corrected text domains
- Improved: Minimum supported WordPress version is now 5.3

= 5.3.0 =
- Added: Useful filters
- Fixed: Removed deprecated mobile_application
- Fixed: MOF issues

= 5.2.0 =
- Added: New `mmnumber` search field type with text, selectbox, and min/max selectbox display modes
- Added: `andfeature` query format for AND-logic feature matching in search queries
- Added: Auto-purge cron for RF-sourced properties not synced within 30 days
- Added: State name/abbreviation expansion in location text search (matches both full and abbreviated US state names)
- Added: PropertyRooms import from RF feed for MLS On The Fly™ listings
- Added: WebP image support for user profile photo uploads
- Improved: OpenHouse date parsing with timezone-aware DateTime, past-event filtering, and cached results
- Improved: Skip MLS ID generation for RF-imported listings via `$generate_mls_id` parameter
- Improved: Broadened shortcode detection in SEF to cover all registered shortcodes
- Improved: Suppress display of private RF listings (`PrivateYn`)
- Improved: Registered `boolean` type in checkbox search widget and `multiselect` in select widget
- Fixed: Broken PHP expression in listing contact form template causing attribute output failure
- Fixed: Double `itemprop` attribute on address microdata element
- Fixed: Fatal errors when property/user columns are absent (null-safe fallback added)
- Fixed: PHP warnings on corrupt or non-image files in EXIF reader
- Fixed: Unintended parent event bubbling on remove-parameter buttons in params activity
- Fixed: Price field min/max inputs rendering outside labeled wrapper in single-currency mode
- Fixed: Undefined index notice for `secondary_email` in agent info template
- Fixed: Premature output from `apply_filters('the_content', ...)` on description field
- Fixed: Sanitized pagination and sort parameters in users query to prevent SQL injection

= 5.1.1 =
- Fixed: OpenHouse issue for MLS On The Fly™ listings
- Fixed: Saving customized UI in settings

= 5.1.0 =
- Added: Add cron to update openhouse tags
- Fixed: Showing county/city list in search widget for MLS On The Fly™ listings
- Fixed: Disable geocoding for MLS On The Fly™ listings

= 5.0.13 =
- Added: cache some queries
- Added: useful hooks
- Fixed: RankMath image issue
- Fixed: Multi-language issue
- Fixed: update X icons
- Fixed: issue with braintree payment

= 5.0.12 =
- Fixed: location search issue

= 5.0.11 =
- Added: SEO improvement
- Fixed: font issue
- Fixed: distance issue for neighborhoods

= 5.0.10 =
- Added: hook for sef service
- Fixed: search issue

= 5.0.9 =
- Fixed: marker icon issue for MOF
- Improved: suggestion speed

= 5.0.8 =
- Added: filters for flex
- Fixed: pagination in print feature

= 5.0.7 =
- Fixed: Known issues

= 5.0.6 =
- Fixed: Known issues

= 5.0.5 =
- Fixed complex issue on property wizard page
- Fixed search issue with sf_multiplelocationtextsearch

= 5.0.4 =
- Fixed: Known issues

= 5.0.3 =
- Fixed listings manager buttons issue

= 5.0.2 =
- Added: some hooks for customizations
- Removed: some warning errors

= 5.0.1 =
- Fixed: Known issues

= 5.0.0 =
- Added: MLS On The Fly™
- Fixed: Known issues

= 4.14.9 =
- Fixed: Known issues

= 4.14.5 =
- Added: Edit button for property details page
- Added: Direct query to RF via sf_on_the_fly
- Added: Filters to customize suggestions
- Added: Actions to customize ajax requests
- Fixed: List of locations for MLS On The Fly™ in search widgets

= 4.14.3 =
- Fixed: Known issues

= 4.14.0 =
- Added: MLS On The Fly™ [PRO]
- Added: Upload field type in flex
- Added: More filters
- Fixed: jQuery issue, no need to install "Enable jQuery Migrate Helper"
- Fixed: PDF image issue for multi-language sites [PRO]
- Fixed: Removed async: false for ajax requests
- Fixed: Reported issues

= 4.13.14 =
- Added: Filters for OG tags
- Added: Filters for geocoding
- Fixed: Update addon issue with PHP 8 [PRO]
- Fixed: Adding existing user to IDX
- Fixed: Reported issues

= 4.13.13 =
- Added: More filters for page builders
- Removed: ZipArchive requirement
- Fixed: image lazy-load issue
- Fixed: Language issue
- Fixed: known issues

= 4.13.10 =
- Added: More filters
- Added: Default value for select type in flex
- Added: advancedlocationtextsearch_v2 filter
- Improved: Search location
- Fixed: UTF8 encode issue
- Fixed: Nearby functionality [PRO]
- Fixed: known issues

= 4.13.5 =
- Added Nullable boolean type
- Added Google key validation
- Fixed PHP 8.2 Compatibility

= 4.13.1 =
* Fixed Search Widget Issue
* Fixed Google Map Issue
* Fixed Divi Theme compatibility issues
* Fixed some JS & JQuery issues
* Improved PDF viewer [PRO]

= 4.13.0 =
* Added PHP 8.x Compatibility
* Added Category Manager to the Listing Types
* Added Colors to the Property Types & Listing Types
* Added settings to keep SEO patterns on the Page title when purging the WPL Cache
* Added Log settings to prevent the logs from being large
* Added Unlimited Map Markers on the Google Map [PRO]
* Fixed conflicts with the Yoast Plugin
* Fixed conflicts with the AIOSEO Plugin
* Fixed some incompatibilities with the RankMath SEO Plugin
* Fixed some Google Map issues
* Fixed some incompatibilities with Page Builders
* Fixed some issues with Gutenberg Editor
* Fixed some JS & JQuery issues
* Fixed some issues with the language files
* Fixed some Issues with PDF Generator [PRO]
* Fixed RSS Issue [PRO]
* Improved DB & Query Optimization
* Improved Built-in SEO Values
* Improved Mobile View
* Improved compatiblity with the Avada Theme

= 4.12.1 =
* Fixed Memory Usage Issue in Search Widget

= 4.12.0 =
* Fixed Some Bugs
* Fixed Some Incompatibility Issues with PHP 8.x
* Fixed Google Map Issues
* Fixed TinyMCE Issues
* Fixed Unit Switcher Issues
* Fixed An Incompatibility Issue with WPML Plugin
* Fixed Session Issue with REST-API
* Improved Some UI Styles
* Added Expired Property Tag in Listing Manager
* Added Ability to Revet Expired Property in Listing Manager
* Added BenchMarker Feature