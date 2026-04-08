<?php
/**
 * AI Tool Definitions - Claude tool-use schema
 */
class AICopilot_Tools
{
    public static function getAll(): array
    {
        return [
            [
                'name' => 'edit_element_text',
                'description' => 'Edit the text content of an element on the current page. Use this to change headings, paragraphs, button text, or any text content.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID'],
                        'field' => ['type' => 'string', 'description' => 'Field name: title, editor, text, button_text'],
                        'value' => ['type' => 'string', 'description' => 'New text content (HTML allowed for editor field)'],
                    ],
                    'required' => ['page_id', 'element_id', 'field', 'value'],
                ],
            ],
            [
                'name' => 'edit_element_style',
                'description' => 'Change a style property of an element. Use for colors, font sizes, backgrounds, padding, etc.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID'],
                        'property' => ['type' => 'string', 'description' => 'CSS property: title_color, background_color, typography_font_size, padding, etc.'],
                        'value' => ['type' => 'string', 'description' => 'New value'],
                    ],
                    'required' => ['page_id', 'element_id', 'property', 'value'],
                ],
            ],
            [
                'name' => 'edit_element_image',
                'description' => 'Change an image on the page. Provide a new image URL.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID'],
                        'image_url' => ['type' => 'string', 'description' => 'New image URL'],
                    ],
                    'required' => ['page_id', 'element_id', 'image_url'],
                ],
            ],
            [
                'name' => 'get_page_editables',
                'description' => 'Get all editable elements on a page with their IDs, types, and current content. Call this FIRST to understand what can be edited.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                    ],
                    'required' => ['page_id'],
                ],
            ],
            [
                'name' => 'add_section',
                'description' => 'Add a new section to a page. Types: hero, features, testimonials, cta, contact, pricing, team, faq, gallery, custom.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'section_type' => ['type' => 'string', 'description' => 'Section type'],
                        'position' => ['type' => 'integer', 'description' => 'Position index (0 = top)'],
                        'content' => ['type' => 'object', 'description' => 'Section content (title, subtitle, items)'],
                    ],
                    'required' => ['page_id', 'section_type'],
                ],
            ],
            [
                'name' => 'remove_section',
                'description' => 'Remove a section from a page by its element ID.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Section element ID to remove'],
                    ],
                    'required' => ['page_id', 'element_id'],
                ],
            ],
            [
                'name' => 'create_page',
                'description' => 'Create a new WordPress page with Elementor sections. Always include sections array so the page has proper layout.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string', 'description' => 'Page title'],
                        'status' => ['type' => 'string', 'description' => 'publish or draft'],
                        'sections' => ['type' => 'array', 'description' => 'Array of sections: [{"type":"hero","content":{"title":"...","subtitle":"...","items":[{"title":"...","text":"..."}]}}]'],
                    ],
                    'required' => ['title'],
                ],
            ],
            [
                'name' => 'update_page_seo',
                'description' => 'Update SEO meta tags for a page.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'meta_title' => ['type' => 'string'],
                        'meta_description' => ['type' => 'string'],
                        'keywords' => ['type' => 'string'],
                    ],
                    'required' => ['page_id'],
                ],
            ],
            [
                'name' => 'set_global_colors',
                'description' => 'Update the website global/brand colors.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'primary' => ['type' => 'string', 'description' => 'Primary brand color (hex)'],
                        'secondary' => ['type' => 'string', 'description' => 'Secondary color (hex)'],
                        'text' => ['type' => 'string', 'description' => 'Text color (hex)'],
                        'accent' => ['type' => 'string', 'description' => 'Accent color (hex)'],
                    ],
                    'required' => ['primary'],
                ],
            ],
            [
                'name' => 'upload_image',
                'description' => 'Upload an image from URL to the media library.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'url' => ['type' => 'string', 'description' => 'Image URL to download'],
                        'alt' => ['type' => 'string', 'description' => 'Alt text for the image'],
                    ],
                    'required' => ['url'],
                ],
            ],
            [
                'name' => 'update_site_settings',
                'description' => 'Update WordPress site settings like site title and tagline.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'blogname' => ['type' => 'string', 'description' => 'Site title'],
                        'blogdescription' => ['type' => 'string', 'description' => 'Site tagline'],
                    ],
                    'required' => [],
                ],
            ],
            [
                'name' => 'get_menu_items',
                'description' => 'Get all WordPress navigation menu items. Use when user wants to see, edit, add, or remove nav links.',
                'input_schema' => ['type' => 'object', 'properties' => [], 'required' => []],
            ],
            [
                'name' => 'edit_menu_item',
                'description' => 'Edit, add, or remove a WordPress navigation menu item (header/footer nav links).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'item_id' => ['type' => 'integer', 'description' => 'Menu item ID (from get_menu_items)'],
                        'action' => ['type' => 'string', 'description' => 'edit, add, or remove'],
                        'title' => ['type' => 'string', 'description' => 'Link text'],
                        'url' => ['type' => 'string', 'description' => 'Link URL'],
                        'menu_id' => ['type' => 'integer', 'description' => 'Menu ID (for add action, optional)'],
                    ],
                    'required' => ['action'],
                ],
            ],
            [
                'name' => 'add_widget',
                'description' => 'Insert ANY Elementor widget into a section. Use for adding social icons, videos, maps, forms, dividers, counters, icon lists, star ratings, progress bars, or any widget.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'section_id' => ['type' => 'string', 'description' => 'Section/container ID to add widget into. If empty, adds to last section.'],
                        'widget_type' => ['type' => 'string', 'description' => 'Elementor widget type: social-icons, icon-list, video, google_maps, divider, spacer, star-rating, progress, counter, alert, image-gallery, tabs, accordion, toggle, html, shortcode, menu-anchor, read-more, etc.'],
                        'settings' => ['type' => 'object', 'description' => 'Widget settings object. Each widget has its own settings structure.'],
                        'position' => ['type' => 'integer', 'description' => 'Position index inside section (-1 = end)'],
                    ],
                    'required' => ['page_id', 'widget_type', 'settings'],
                ],
            ],
            [
                'name' => 'edit_repeater',
                'description' => 'Edit repeater/list items inside widgets (slides, tabs, accordion items, icon lists, social icons, price lists). Use for editing, adding, or removing items in lists.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Widget element ID'],
                        'field' => ['type' => 'string', 'description' => 'Repeater field name: slides, tabs, icon_list, social_icon_list, price_list, gallery, etc.'],
                        'index' => ['type' => 'integer', 'description' => 'Item index (0-based)'],
                        'item_field' => ['type' => 'string', 'description' => 'Field inside the item to edit'],
                        'value' => ['type' => 'string', 'description' => 'New value (for edit) or object (for add)'],
                        'action' => ['type' => 'string', 'description' => 'edit, add, or remove'],
                    ],
                    'required' => ['page_id', 'element_id', 'field', 'action'],
                ],
            ],
            [
                'name' => 'get_products',
                'description' => 'Get WooCommerce products list. Use when user asks about products, prices, inventory.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'count' => ['type' => 'integer', 'description' => 'Number of products to fetch (default 20)'],
                        'search' => ['type' => 'string', 'description' => 'Search by product name'],
                    ],
                    'required' => [],
                ],
            ],
            [
                'name' => 'edit_product',
                'description' => 'Edit a WooCommerce product (name, price, description, stock, SKU). Use when user wants to change product details.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'product_id' => ['type' => 'integer', 'description' => 'WooCommerce product ID'],
                        'name' => ['type' => 'string', 'description' => 'Product name'],
                        'price' => ['type' => 'string', 'description' => 'Regular price'],
                        'sale_price' => ['type' => 'string', 'description' => 'Sale price'],
                        'description' => ['type' => 'string', 'description' => 'Full product description (HTML)'],
                        'short_description' => ['type' => 'string', 'description' => 'Short description'],
                        'sku' => ['type' => 'string', 'description' => 'Product SKU'],
                        'stock_status' => ['type' => 'string', 'description' => 'instock or outofstock'],
                        'stock_quantity' => ['type' => 'integer', 'description' => 'Stock quantity'],
                    ],
                    'required' => ['product_id'],
                ],
            ],
            [
                'name' => 'move_section',
                'description' => 'Move a section up or down on the page. Use this when user wants to reorder, swap, or rearrange sections.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Section/container element ID to move'],
                        'direction' => ['type' => 'string', 'description' => '"up" or "down"'],
                    ],
                    'required' => ['page_id', 'element_id', 'direction'],
                ],
            ],
            [
                'name' => 'apply_site_theme',
                'description' => 'Apply a complete color theme across the ENTIRE website (all pages, headers, footers, templates, global colors). Use this when the user wants to change the website colors, color scheme, theme, or brand colors. This is site-wide - it changes EVERYTHING.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'Current page ID (for undo snapshot)'],
                        'bg_color' => ['type' => 'string', 'description' => 'Background/primary dark color for sections (hex, e.g. "#0F172A")'],
                        'accent_color' => ['type' => 'string', 'description' => 'Accent/highlight color for buttons, links, icons (hex, e.g. "#6366F1")'],
                        'text_color' => ['type' => 'string', 'description' => 'Text color for headings and paragraphs (hex, e.g. "#F8FAFC")'],
                        'font' => ['type' => 'string', 'description' => 'Font family name (e.g. "Inter", "Poppins"). Leave empty to keep current fonts.'],
                    ],
                    'required' => ['bg_color', 'accent_color', 'text_color'],
                ],
            ],
        ];
    }
}
