<?php

namespace App\Services\AiCodeGenerator;

/**
 * Defines tools available to Claude for code generation projects.
 * Claude calls these tools to create/edit/delete files in a project.
 */
class ToolRegistry
{
    public static function getTools(): array
    {
        return [
            [
                'name' => 'create_file',
                'description' => 'Create a new file in the project. Use this for new files that do not exist yet.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'path' => [
                            'type' => 'string',
                            'description' => 'File path relative to project root (e.g., "index.html", "css/style.css", "js/app.js")',
                        ],
                        'content' => [
                            'type' => 'string',
                            'description' => 'Complete file content',
                        ],
                    ],
                    'required' => ['path', 'content'],
                ],
            ],
            [
                'name' => 'update_file',
                'description' => 'Update/overwrite an existing file with new content. Use this when modifying files that already exist.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'path' => [
                            'type' => 'string',
                            'description' => 'File path relative to project root',
                        ],
                        'content' => [
                            'type' => 'string',
                            'description' => 'New complete file content (replaces existing)',
                        ],
                    ],
                    'required' => ['path', 'content'],
                ],
            ],
            [
                'name' => 'delete_file',
                'description' => 'Delete a file from the project.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'path' => [
                            'type' => 'string',
                            'description' => 'File path to delete',
                        ],
                    ],
                    'required' => ['path'],
                ],
            ],
            [
                'name' => 'read_file',
                'description' => 'Read the current content of a file. Use this before updating to understand existing code.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'path' => [
                            'type' => 'string',
                            'description' => 'File path to read',
                        ],
                    ],
                    'required' => ['path'],
                ],
            ],
        ];
    }
}
