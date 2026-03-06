<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WxrImporterService
{
    private string $dbHost;
    private string $dbUser;
    private string $dbPass;

    public function __construct()
    {
        $this->dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $this->dbUser = config('database.connections.mysql.username', 'root');
        $this->dbPass = config('database.connections.mysql.password', '');
    }

    /**
     * Import a WXR (WordPress eXtended RSS) XML file into a WordPress database.
     *
     * @return array ['posts' => count, 'attachments' => count, 'terms' => count, 'id_map' => [...]]
     */
    public function import(string $dbName, string $xmlPath, string $siteUrl): array
    {
        if (!file_exists($xmlPath)) {
            throw new \RuntimeException("WXR file not found: {$xmlPath}");
        }

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $xml = simplexml_load_file($xmlPath, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_COMPACT);
        if (!$xml) {
            throw new \RuntimeException("Failed to parse WXR XML: {$xmlPath}");
        }

        $channel = $xml->channel;
        $wpNs = 'http://wordpress.org/export/1.2/';
        $contentNs = 'http://purl.org/rss/1.0/modules/content/';
        $excerptNs = 'http://wordpress.org/export/1.2/excerpt/';
        $dcNs = 'http://purl.org/dc/elements/1.1/';

        $stats = ['posts' => 0, 'attachments' => 0, 'terms' => 0];
        $idMap = []; // old_id => new_id

        // Phase 1: Import terms (categories, tags, nav_menu terms)
        $termIdMap = [];

        foreach ($channel->children($wpNs) as $name => $node) {
            if ($name === 'category') {
                $termId = (int) $node->term_id;
                $nicename = (string) $node->category_nicename;
                $catName = (string) $node->cat_name;
                $parent = (string) $node->category_parent;

                $newTermId = $this->insertTerm($pdo, $catName, $nicename, 'category', $parent);
                if ($newTermId) {
                    $termIdMap[$termId] = $newTermId;
                    $stats['terms']++;
                }
            } elseif ($name === 'tag') {
                $termId = (int) $node->tag_slug ? (int) $node->term_id : 0;
                $slug = (string) $node->tag_slug;
                $tagName = (string) $node->tag_name;

                $newTermId = $this->insertTerm($pdo, $tagName, $slug, 'post_tag');
                if ($newTermId) {
                    $termIdMap[$termId] = $newTermId;
                    $stats['terms']++;
                }
            } elseif ($name === 'term') {
                $termId = (int) $node->term_id;
                $taxonomy = (string) $node->term_taxonomy;
                $slug = (string) $node->term_slug;
                $termName = (string) $node->term_name;
                $parent = (string) $node->term_parent;

                $newTermId = $this->insertTerm($pdo, $termName, $slug, $taxonomy, $parent);
                if ($newTermId) {
                    $termIdMap[$termId] = $newTermId;
                    $stats['terms']++;
                }
            }
        }

        // Phase 2: Import items (posts, pages, attachments, nav_menu_items, CPTs)
        foreach ($channel->item as $item) {
            $wp = $item->children($wpNs);
            $dc = $item->children($dcNs);

            $oldId = (int) $wp->post_id;
            $postType = (string) $wp->post_type;
            $title = (string) $item->title;
            $content = (string) $item->children($contentNs)->encoded;
            $excerpt = (string) $item->children($excerptNs)->encoded;
            $postName = (string) $wp->post_name;
            $status = (string) $wp->status;
            $postParent = (int) $wp->post_parent;
            $menuOrder = (int) $wp->menu_order;
            $postDate = (string) $wp->post_date;
            $postDateGmt = (string) $wp->post_date_gmt;
            $postModified = (string) $wp->post_modified;
            $postModifiedGmt = (string) $wp->post_modified_gmt;
            $commentStatus = (string) $wp->comment_status;
            $pingStatus = (string) $wp->ping_status;
            $postPassword = (string) $wp->post_password;
            $guid = (string) $item->guid;

            // Replace demo domain in content and guid
            $content = $this->replaceDomain($content, $siteUrl);
            $guid = $this->replaceDomain($guid, $siteUrl);

            // Handle attachment URL
            $attachmentUrl = '';
            if ($postType === 'attachment') {
                $attachmentUrl = (string) $wp->attachment_url;
                $attachmentUrl = $this->replaceDomain($attachmentUrl, $siteUrl);
            }

            // Remap post_parent
            if ($postParent > 0 && isset($idMap[$postParent])) {
                $postParent = $idMap[$postParent];
            }

            // Insert post
            $newId = $this->insertPost($pdo, [
                'post_author' => 1,
                'post_date' => $postDate,
                'post_date_gmt' => $postDateGmt,
                'post_content' => $content,
                'post_title' => $title,
                'post_excerpt' => $excerpt,
                'post_status' => $status,
                'comment_status' => $commentStatus,
                'ping_status' => $pingStatus,
                'post_password' => $postPassword,
                'post_name' => $postName,
                'post_modified' => $postModified,
                'post_modified_gmt' => $postModifiedGmt,
                'post_parent' => $postParent,
                'guid' => $guid,
                'menu_order' => $menuOrder,
                'post_type' => $postType,
            ]);

            if ($newId) {
                $idMap[$oldId] = $newId;

                if ($postType === 'attachment') {
                    $stats['attachments']++;
                } else {
                    $stats['posts']++;
                }

                // Insert postmeta
                foreach ($wp->postmeta as $meta) {
                    $metaKey = (string) $meta->meta_key;
                    $metaValue = (string) $meta->meta_value;

                    // Replace domain in meta values
                    $metaValue = $this->replaceDomain($metaValue, $siteUrl);

                    $this->insertPostMeta($pdo, $newId, $metaKey, $metaValue);
                }

                // Set attachment metadata if applicable
                if ($postType === 'attachment' && $attachmentUrl) {
                    // Extract relative path from URL
                    $uploadsPos = strpos($attachmentUrl, 'wp-content/uploads/');
                    if ($uploadsPos !== false) {
                        $relPath = substr($attachmentUrl, $uploadsPos + strlen('wp-content/uploads/'));
                        // Check if _wp_attached_file was already set via postmeta
                        $existing = $pdo->prepare("SELECT meta_id FROM wp_postmeta WHERE post_id = ? AND meta_key = '_wp_attached_file'");
                        $existing->execute([$newId]);
                        if (!$existing->fetch()) {
                            $this->insertPostMeta($pdo, $newId, '_wp_attached_file', $relPath);
                        }
                    }
                }

                // Handle term relationships
                foreach ($wp->children($wpNs) as $childName => $childNode) {
                    // WXR uses <category domain="..." nicename="..."> for term relationships
                }
            }
        }

        // Handle term relationships from <category> elements within items
        foreach ($channel->item as $item) {
            $wp = $item->children($wpNs);
            $oldId = (int) $wp->post_id;

            if (!isset($idMap[$oldId])) continue;
            $newPostId = $idMap[$oldId];

            foreach ($item->category as $cat) {
                $attrs = $cat->attributes();
                $domain = (string) ($attrs->domain ?? 'category');
                $nicename = (string) ($attrs->nicename ?? '');

                if ($nicename) {
                    $this->assignTermToPost($pdo, $newPostId, $nicename, $domain);
                }
            }
        }

        // Phase 3: Import nav menus — remap old post IDs in nav_menu_item meta
        $this->remapNavMenuItems($pdo, $idMap);

        // Phase 4: Set front page if we found one
        $this->setFrontPage($pdo, $idMap, $channel, $wpNs);

        // Phase 5: Replace any remaining demo domain URLs in _elementor_data and all postmeta
        if ($this->demoDomain) {
            $demoDomains = [$this->demoDomain];
            // Add http variant if it's https
            if (str_starts_with($this->demoDomain, 'https://')) {
                $demoDomains[] = str_replace('https://', 'http://', $this->demoDomain);
            }
            // Also add the bare domain (without protocol)
            $parsed = parse_url($this->demoDomain);
            $bareDomain = ($parsed['host'] ?? '') . ($parsed['path'] ?? '');
            if ($bareDomain) {
                $demoDomains[] = $bareDomain;
            }

            foreach ($demoDomains as $dd) {
                // Elementor data (JSON, not serialized)
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, ?, ?) WHERE meta_value LIKE ? AND (meta_key = '_elementor_data' OR meta_key LIKE '_%_data')")
                    ->execute([$dd, $siteUrl, '%' . $dd . '%']);

                // Non-serialized postmeta
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, ?, ?) WHERE meta_value LIKE ? AND meta_value NOT LIKE 'a:%' AND meta_value NOT LIKE 's:%'")
                    ->execute([$dd, $siteUrl, '%' . $dd . '%']);

                // Serialized postmeta — handle via PHP
                $serializedStmt = $pdo->prepare("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_value LIKE ? AND (meta_value LIKE 'a:%' OR meta_value LIKE 's:%')");
                $serializedStmt->execute(['%' . $dd . '%']);
                while ($sRow = $serializedStmt->fetch(\PDO::FETCH_ASSOC)) {
                    $val = $sRow['meta_value'];
                    $un = @unserialize($val);
                    if ($un !== false) {
                        $json = json_encode($un);
                        $json = str_replace(str_replace('/', '\\/', $dd), str_replace('/', '\\/', $siteUrl), $json);
                        $json = str_replace($dd, $siteUrl, $json);
                        $newVal = serialize(json_decode($json, true));
                        if ($newVal !== $val) {
                            $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")->execute([$newVal, $sRow['meta_id']]);
                        }
                    }
                }

                // Options (non-siteurl/home)
                $pdo->prepare("UPDATE wp_options SET option_value = REPLACE(option_value, ?, ?) WHERE option_value LIKE ? AND option_name NOT IN ('siteurl','home') AND option_value NOT LIKE 'a:%' AND option_value NOT LIKE 's:%'")
                    ->execute([$dd, $siteUrl, '%' . $dd . '%']);

                // Serialized options
                $optStmt = $pdo->prepare("SELECT option_id, option_value FROM wp_options WHERE option_value LIKE ? AND (option_value LIKE 'a:%' OR option_value LIKE 's:%')");
                $optStmt->execute(['%' . $dd . '%']);
                while ($oRow = $optStmt->fetch(\PDO::FETCH_ASSOC)) {
                    $val = $oRow['option_value'];
                    $un = @unserialize($val);
                    if ($un !== false) {
                        $json = json_encode($un);
                        $json = str_replace(str_replace('/', '\\/', $dd), str_replace('/', '\\/', $siteUrl), $json);
                        $json = str_replace($dd, $siteUrl, $json);
                        $newVal = serialize(json_decode($json, true));
                        if ($newVal !== $val) {
                            $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_id = ?")->execute([$newVal, $oRow['option_id']]);
                        }
                    }
                }

                // Posts content + guid
                $pdo->prepare("UPDATE wp_posts SET post_content = REPLACE(post_content, ?, ?) WHERE post_content LIKE ?")->execute([$dd, $siteUrl, '%' . $dd . '%']);
                $pdo->prepare("UPDATE wp_posts SET guid = REPLACE(guid, ?, ?) WHERE guid LIKE ?")->execute([$dd, $siteUrl, '%' . $dd . '%']);
            }

            Log::info("WXR import: cleaned demo domain references ({$this->demoDomain} → {$siteUrl})");
        }

        $stats['id_map'] = $idMap;

        Log::info("WXR import complete: {$stats['posts']} posts, {$stats['attachments']} attachments, {$stats['terms']} terms");

        return $stats;
    }

    /**
     * Detect demo domain(s) from the XML and register for replacement.
     */
    private string $demoDomain = '';

    public function setDemoDomain(string $domain): self
    {
        $this->demoDomain = rtrim($domain, '/');
        return $this;
    }

    private function replaceDomain(string $text, string $siteUrl): string
    {
        if (empty($text) || empty($this->demoDomain)) {
            return $text;
        }

        // Replace the demo domain (both http and https variants)
        $siteUrl = rtrim($siteUrl, '/');
        $text = str_replace($this->demoDomain, $siteUrl, $text);

        // Also replace http variant if demo was https
        if (str_starts_with($this->demoDomain, 'https://')) {
            $httpVariant = 'http://' . substr($this->demoDomain, 8);
            $text = str_replace($httpVariant, $siteUrl, $text);
        }

        return $text;
    }

    private function insertTerm(\PDO $pdo, string $name, string $slug, string $taxonomy, string $parentSlug = ''): ?int
    {
        // Check if term exists
        $stmt = $pdo->prepare("SELECT t.term_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = ? AND tt.taxonomy = ?");
        $stmt->execute([$slug, $taxonomy]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            return (int) $existing;
        }

        // Insert term
        $stmt = $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)");
        $stmt->execute([$name, $slug]);
        $termId = (int) $pdo->lastInsertId();

        // Resolve parent
        $parentId = 0;
        if ($parentSlug) {
            $stmt = $pdo->prepare("SELECT t.term_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = ? AND tt.taxonomy = ?");
            $stmt->execute([$parentSlug, $taxonomy]);
            $parentId = (int) $stmt->fetchColumn();
        }

        // Insert taxonomy
        $stmt = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, ?, '', ?, 0)");
        $stmt->execute([$termId, $taxonomy, $parentId]);

        return $termId;
    }

    private function insertPost(\PDO $pdo, array $data): ?int
    {
        $stmt = $pdo->prepare("
            INSERT INTO wp_posts (
                post_author, post_date, post_date_gmt, post_content, post_title,
                post_excerpt, post_status, comment_status, ping_status, post_password,
                post_name, post_modified, post_modified_gmt, post_parent, guid,
                menu_order, post_type, post_content_filtered, to_ping, pinged
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', '', '')
        ");

        $stmt->execute([
            $data['post_author'],
            $data['post_date'],
            $data['post_date_gmt'],
            $data['post_content'],
            $data['post_title'],
            $data['post_excerpt'],
            $data['post_status'],
            $data['comment_status'],
            $data['ping_status'],
            $data['post_password'],
            $data['post_name'],
            $data['post_modified'],
            $data['post_modified_gmt'],
            $data['post_parent'],
            $data['guid'],
            $data['menu_order'],
            $data['post_type'],
        ]);

        return (int) $pdo->lastInsertId() ?: null;
    }

    private function insertPostMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    private function assignTermToPost(\PDO $pdo, int $postId, string $termSlug, string $taxonomy): void
    {
        // Find taxonomy term ID
        $stmt = $pdo->prepare("
            SELECT tt.term_taxonomy_id FROM wp_term_taxonomy tt
            JOIN wp_terms t ON t.term_id = tt.term_id
            WHERE t.slug = ? AND tt.taxonomy = ?
        ");
        $stmt->execute([$termSlug, $taxonomy]);
        $ttId = $stmt->fetchColumn();

        if (!$ttId) return;

        // Check if relationship exists
        $stmt = $pdo->prepare("SELECT object_id FROM wp_term_relationships WHERE object_id = ? AND term_taxonomy_id = ?");
        $stmt->execute([$postId, $ttId]);
        if ($stmt->fetch()) return;

        // Insert relationship
        $stmt = $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)");
        $stmt->execute([$postId, $ttId]);

        // Update count
        $pdo->prepare("UPDATE wp_term_taxonomy SET count = count + 1 WHERE term_taxonomy_id = ?")->execute([$ttId]);
    }

    private function remapNavMenuItems(\PDO $pdo, array $idMap): void
    {
        // Update _menu_item_object_id references
        $stmt = $pdo->prepare("
            SELECT pm.meta_id, pm.meta_value
            FROM wp_postmeta pm
            JOIN wp_posts p ON p.ID = pm.post_id
            WHERE p.post_type = 'nav_menu_item' AND pm.meta_key = '_menu_item_object_id'
        ");
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $oldObjId = (int) $row['meta_value'];
            if (isset($idMap[$oldObjId])) {
                $update = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
                $update->execute([(string) $idMap[$oldObjId], $row['meta_id']]);
            }
        }

        // Update _menu_item_menu_item_parent references
        $stmt = $pdo->prepare("
            SELECT pm.meta_id, pm.meta_value
            FROM wp_postmeta pm
            JOIN wp_posts p ON p.ID = pm.post_id
            WHERE p.post_type = 'nav_menu_item' AND pm.meta_key = '_menu_item_menu_item_parent'
        ");
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $oldParent = (int) $row['meta_value'];
            if ($oldParent > 0 && isset($idMap[$oldParent])) {
                $update = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
                $update->execute([(string) $idMap[$oldParent], $row['meta_id']]);
            }
        }
    }

    private function setFrontPage(\PDO $pdo, array $idMap, $channel, string $wpNs): void
    {
        // Look for common front page names
        $frontPageNames = ['home', 'front-page', 'homepage', 'home-page', 'barab-home-2', 'main-home'];

        foreach ($channel->item as $item) {
            $wp = $item->children($wpNs);
            $postType = (string) $wp->post_type;
            $postName = (string) $wp->post_name;
            $oldId = (int) $wp->post_id;

            if ($postType === 'page' && isset($idMap[$oldId])) {
                if (in_array($postName, $frontPageNames) || stripos((string) $item->title, 'home') !== false) {
                    $newId = $idMap[$oldId];

                    // Set as static front page
                    $this->setOption($pdo, 'show_on_front', 'page');
                    $this->setOption($pdo, 'page_on_front', (string) $newId);
                    Log::info("Set front page to: {$item->title} (ID {$newId})");
                    return;
                }
            }
        }
    }

    private function setOption(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT option_id FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);

        if ($stmt->fetchColumn()) {
            $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?")->execute([$value, $key]);
        } else {
            $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')")->execute([$key, $value]);
        }
    }

    /**
     * Import Redux theme options from a JSON file.
     */
    public function importReduxOptions(string $dbName, string $jsonPath, string $optionName = ''): void
    {
        if (!file_exists($jsonPath)) {
            Log::warning("Redux options file not found: {$jsonPath}");
            return;
        }

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $json = file_get_contents($jsonPath);
        $options = json_decode($json, true);

        if (!$options) {
            Log::warning("Failed to parse Redux options JSON: {$jsonPath}");
            return;
        }

        // Auto-detect option name from first key or use theme name
        if (!$optionName) {
            // Common Redux option names
            $candidates = ['barab', 'transland', 'geoport', 'redux_options'];
            foreach ($candidates as $candidate) {
                if (isset($options[$candidate])) {
                    $optionName = $candidate;
                    $options = $options[$candidate];
                    break;
                }
            }

            if (!$optionName) {
                // Store the whole JSON as a single option
                $optionName = 'theme_options';
            }
        }

        $serialized = serialize($options);
        $this->setOption($pdo, $optionName, $serialized);
        Log::info("Imported Redux options as: {$optionName}");
    }

    /**
     * Import Codestar framework options from a serialized PHP file.
     */
    public function importCodestarOptions(string $dbName, string $optionsPath, string $optionName = 'theme_options'): void
    {
        if (!file_exists($optionsPath)) {
            Log::warning("Codestar options file not found: {$optionsPath}");
            return;
        }

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $content = file_get_contents($optionsPath);

        // Try unserialize first (Codestar uses serialized PHP)
        $data = @unserialize($content);
        if ($data !== false) {
            $this->setOption($pdo, $optionName, $content);
            Log::info("Imported Codestar serialized options as: {$optionName}");
            return;
        }

        // Try JSON decode
        $data = json_decode($content, true);
        if ($data) {
            $this->setOption($pdo, $optionName, serialize($data));
            Log::info("Imported Codestar JSON options as: {$optionName}");
            return;
        }

        // Store raw
        $this->setOption($pdo, $optionName, $content);
        Log::info("Imported raw options as: {$optionName}");
    }
}
