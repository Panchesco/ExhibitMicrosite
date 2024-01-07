# Exhibit Microsite

We wanted to utilize Omeka Classic's Exhibit Builder plugin as the entry point for a digital archive. We needed a way to present Omeka Classic Exhibits and Items in a way that gives us more control over labeling and URL structure. For example, we might want to label a group of Exhibit Pages "Collections" or display items from multiple collections on a page named "Browse" or something similar that may or may not match Omeka's native nomenclature. We also wanted to be able to filter and search content at the Exhibit level, presenting users with only the Omeka collections and items represented in this particular digital archive.

The Exhibit Microsite plugin presents a selected Omeka Classic exhibit and its pages as an intranet-type experience where users remain inside an area that presents the Exhibit's included collections in an active theme across Item, Collection, and Search pages. URLs and navigational elements — such as a breadcrumb trail — maintain the parent-child hierarchy of the Exhibit Pages.

The plugin also adds four Bootstrap 5-friendly layout blocks:

- Flex Text Block
- Flex File Block
- Flex File with Text Block
- Flex Slider Gallery

These Flex blocks allow exhibit editors to style and configure the blocks and create multicolumn responsive page layouts using [Bootstrap 5's Flex Utility](https://getbootstrap.com/docs/5.0/utilities/flex/#enable-flex-behaviors).

## Requirements

- Active installation of Omeka Classic [Exhibit Builder](https://omeka.org/classic/plugins/ExhibitBuilder/) plugin.

## Getting Started

- Install and Activate the Exhibit Microsite plugin.

- Create and Configure an Exhibit. Currently, the plugin works with the Border Narrative theme, so select and configure that theme for use with the Exhibit.

- Create the landing pages for the Exhibit. These are the parent pages that will appear in the global navigation. Adding content to these pages is optional before creating and configuring the Exhibit Microsite, but seeing the page structure is helpful. Also important:
- The page for listing Exhibit child pages must use the slug "collections."
  The page for browsing and filtering collection items must use the slug "browse."

- Use the drag-and-drop feature to order the pages.

### More About Exhibit Pages Hierarchy

- An Exhibit Microsite uses the top-level Exhibit Pages of an Exhibit in its header navigation and as its landing pages. If the Exhibit uses the Summary page for its starting page, you can configure the Exhibit Microsite to display it as the first link in the global navigation.

## Creating and Configuring an Exhibit Microsite

- Follow the Exhibit Microsites link in the Omeka Classic Control Panel sidebar.
- Select "Add an Exhibit Microsite" or "Edit" an existing Exhibit Microsite.
- Select an existing Exhibit.
- Check the names of Omeka Classic Collections to make them available on the Exhibit Microsite Browse and Search pages.
- Complete the other options and save.
- If the Exhibit is public, it is now available at domainname.com/exhibits/show/exhibit-slug

## Notes

### Flex Blocks

The Exhibit Microsite Flex layout blocks are available on all exhibit pages; however, you'll need to modify your theme's exhibit page files to use Bootstrap 5 class declarations for them to render correctly.

### Editing Exhibit Microsite View Files

If you're PHP savvy, you can customize the Exhibit Microsite view files and add them to any theme, [much the same as you can customize Exhibit Builder view files](https://omeka.readthedocs.io/en/latest/Tutorials/extendingExhibitBuilder.html).
The plugin will look for the files first inside the Exhibit's active theme, so within your theme, create an exhibit-microsite directory with the following structure:

| ExhibitMicrosite
| | views
| | |public
| | | | collection
| | | | | filters
| | | | | | item-types.php
| | | | | | active-filters.php
| | | | | | creator.php
| | | | | | collection-id.php
| | | | | browse.php
| | | | exhibit-pages
| | | | | index.php
| | | | | show.php
| | | | |index-listing.php
| | | | search
| | | | | index.php
| | | | | advanced-search.php
| | | | | simple-search.php
| | | | exhibit
| | | | | summary.php
| | | | items
| | | | | show.php
| | | | | browse.php
| | | | sitewide
| | | | | search-form.php
| | | | | pagination.php
| | | | | header-nav.php
| | | | | option-defaults.php
| | | | | microsite-header.php
