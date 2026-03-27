<?php

namespace App\Services\AiCopilot;

/**
 * Defines all tools available to the AI Copilot via Claude Tool-Use.
 * Each tool maps to a WpBridgeService method.
 */
class ActionRegistry
{
    /**
     * Get all tool definitions for Claude API tool_use.
     */
    public static function getTools(): array
    {
        return [
            // ─── Content Editing ───
            [
                'name' => 'edit_element_text',
                'description' => 'Edit text content of an Elementor widget on a page. Use this for headings, text blocks, buttons, etc.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID (7-char hex)'],
                        'field' => ['type' => 'string', 'description' => 'Field name to edit (e.g., title, editor, text, description_text)'],
                        'value' => ['type' => 'string', 'description' => 'New text value'],
                    ],
                    'required' => ['page_id', 'element_id', 'field', 'value'],
                ],
            ],
            [
                'name' => 'edit_element_style',
                'description' => 'Change a style property of an Elementor element (color, font size, background, padding, etc).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID'],
                        'property' => ['type' => 'string', 'description' => 'CSS/Elementor property (e.g., title_color, background_color, typography_font_size)'],
                        'value' => ['type' => 'string', 'description' => 'New value (e.g., #FF0000, 24px, Poppins)'],
                    ],
                    'required' => ['page_id', 'element_id', 'property', 'value'],
                ],
            ],
            [
                'name' => 'edit_element_image',
                'description' => 'Change the image of an Elementor image widget or background image of a container.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'element_id' => ['type' => 'string', 'description' => 'Elementor element ID'],
                        'image_url' => ['type' => 'string', 'description' => 'New image URL (will be uploaded to media library)'],
                        'alt_text' => ['type' => 'string', 'description' => 'Alt text for the image'],
                    ],
                    'required' => ['page_id', 'element_id', 'image_url'],
                ],
            ],

            // ─── Page editables (read) ───
            [
                'name' => 'get_page_editables',
                'description' => 'Get all editable elements on a page. Use this to see what can be changed before making edits.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                    ],
                    'required' => ['page_id'],
                ],
            ],

            // ─── Section Management ───
            [
                'name' => 'add_section',
                'description' => 'Add a new Elementor section/container to a page. Provide the full section JSON.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'position' => ['type' => 'integer', 'description' => 'Position index (0 = top, -1 = bottom)'],
                        'section_type' => [
                            'type' => 'string',
                            'description' => 'Type of section to add',
                            'enum' => ['hero', 'features', 'testimonials', 'cta', 'contact', 'pricing', 'team', 'faq', 'gallery', 'custom'],
                        ],
                        'content' => ['type' => 'object', 'description' => 'Content for the section (varies by type)'],
                    ],
                    'required' => ['page_id', 'section_type'],
                ],
            ],
            [
                'name' => 'remove_section',
                'description' => 'Remove a section/container from a page by its element ID.',
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
                'name' => 'reorder_sections',
                'description' => 'Reorder sections on a page. Provide element IDs in desired order.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'order' => [
                            'type' => 'array',
                            'items' => ['type' => 'string'],
                            'description' => 'Array of section element IDs in desired order',
                        ],
                    ],
                    'required' => ['page_id', 'order'],
                ],
            ],

            // ─── Page Management ───
            [
                'name' => 'create_page',
                'description' => 'Create a new WordPress page with a title. Optionally specify content.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string', 'description' => 'Page title'],
                        'content' => ['type' => 'string', 'description' => 'Page content (HTML or plain text)'],
                        'status' => ['type' => 'string', 'enum' => ['publish', 'draft'], 'description' => 'Page status'],
                    ],
                    'required' => ['title'],
                ],
            ],
            [
                'name' => 'update_page_title',
                'description' => 'Update the title of an existing page.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'title' => ['type' => 'string', 'description' => 'New page title'],
                    ],
                    'required' => ['page_id', 'title'],
                ],
            ],
            [
                'name' => 'delete_page',
                'description' => 'Delete a page (moves to trash by default).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'permanent' => ['type' => 'boolean', 'description' => 'Permanently delete (skip trash)'],
                    ],
                    'required' => ['page_id'],
                ],
            ],

            // ─── Global Styles ───
            [
                'name' => 'set_global_colors',
                'description' => 'Update Elementor global/brand colors. Changes apply across all pages.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'colors' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    '_id' => ['type' => 'string'],
                                    'title' => ['type' => 'string'],
                                    'color' => ['type' => 'string', 'description' => 'Hex color like #FF0000'],
                                ],
                            ],
                            'description' => 'Array of global color definitions',
                        ],
                    ],
                    'required' => ['colors'],
                ],
            ],
            [
                'name' => 'get_global_colors',
                'description' => 'Get current Elementor global colors.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => (object)[],
                ],
            ],
            [
                'name' => 'set_global_fonts',
                'description' => 'Update Elementor global font settings.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'fonts' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    '_id' => ['type' => 'string'],
                                    'title' => ['type' => 'string'],
                                    'typography_font_family' => ['type' => 'string'],
                                    'typography_font_weight' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ],
                    'required' => ['fonts'],
                ],
            ],

            // ─── SEO ───
            [
                'name' => 'update_page_seo',
                'description' => 'Update SEO meta tags for a page (title, description, keywords).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                        'seo_title' => ['type' => 'string', 'description' => 'SEO title tag'],
                        'seo_description' => ['type' => 'string', 'description' => 'SEO meta description'],
                        'focus_keyword' => ['type' => 'string', 'description' => 'Focus keyword for SEO'],
                    ],
                    'required' => ['page_id'],
                ],
            ],
            [
                'name' => 'get_page_seo',
                'description' => 'Get current SEO meta for a page.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'page_id' => ['type' => 'integer', 'description' => 'WordPress page ID'],
                    ],
                    'required' => ['page_id'],
                ],
            ],

            // ─── WooCommerce ───
            [
                'name' => 'create_product',
                'description' => 'Create a new WooCommerce product.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'name' => ['type' => 'string', 'description' => 'Product name'],
                        'price' => ['type' => 'string', 'description' => 'Regular price'],
                        'sale_price' => ['type' => 'string', 'description' => 'Sale price (optional)'],
                        'description' => ['type' => 'string', 'description' => 'Product description'],
                        'short_description' => ['type' => 'string', 'description' => 'Short description'],
                        'sku' => ['type' => 'string', 'description' => 'Product SKU'],
                        'image_url' => ['type' => 'string', 'description' => 'Product image URL'],
                    ],
                    'required' => ['name', 'price'],
                ],
            ],
            [
                'name' => 'update_product',
                'description' => 'Update an existing WooCommerce product.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'product_id' => ['type' => 'integer', 'description' => 'WooCommerce product ID'],
                        'name' => ['type' => 'string'],
                        'price' => ['type' => 'string'],
                        'sale_price' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'short_description' => ['type' => 'string'],
                    ],
                    'required' => ['product_id'],
                ],
            ],
            [
                'name' => 'list_products',
                'description' => 'List WooCommerce products.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'per_page' => ['type' => 'integer', 'description' => 'Items per page (default 20)'],
                    ],
                ],
            ],

            // ─── Plugins ───
            [
                'name' => 'install_plugin',
                'description' => 'Install a WordPress plugin from the plugin repository.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'slug' => ['type' => 'string', 'description' => 'Plugin slug (e.g., "contact-form-7")'],
                        'activate' => ['type' => 'boolean', 'description' => 'Activate after install (default true)'],
                    ],
                    'required' => ['slug'],
                ],
            ],

            // ─── Media ───
            [
                'name' => 'upload_image',
                'description' => 'Upload an image from URL to the WordPress media library.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'image_url' => ['type' => 'string', 'description' => 'Image URL to upload'],
                        'alt_text' => ['type' => 'string', 'description' => 'Alt text'],
                        'title' => ['type' => 'string', 'description' => 'Image title'],
                    ],
                    'required' => ['image_url'],
                ],
            ],

            // ─── Menu ───
            [
                'name' => 'get_menus',
                'description' => 'Get all navigation menus and their items.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => (object)[],
                ],
            ],
            [
                'name' => 'update_menu',
                'description' => 'Update a navigation menu with new items.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'menu_id' => ['type' => 'integer', 'description' => 'Menu ID'],
                        'items' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                    'url' => ['type' => 'string'],
                                    'type' => ['type' => 'string', 'enum' => ['custom', 'post_type']],
                                    'object' => ['type' => 'string'],
                                    'object_id' => ['type' => 'integer'],
                                    'order' => ['type' => 'integer'],
                                ],
                            ],
                        ],
                    ],
                    'required' => ['menu_id', 'items'],
                ],
            ],

            // ─── Site Settings ───
            [
                'name' => 'update_site_settings',
                'description' => 'Update WordPress site settings (site title, tagline, etc).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'blogname' => ['type' => 'string', 'description' => 'Site title'],
                        'blogdescription' => ['type' => 'string', 'description' => 'Site tagline'],
                    ],
                ],
            ],
        ];
    }
}
