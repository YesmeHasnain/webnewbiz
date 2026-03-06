<?php

namespace App\Console\Commands;

use App\Services\MasterThemeBuilder;
use Illuminate\Console\Command;

class BuildMasterThemesCommand extends Command
{
    protected $signature = 'build:masters
        {slug? : Build a specific layout by slug (e.g., noir)}
        {--list : Show status of all layouts}
        {--fresh : Drop and rebuild (use with slug)}
        {--all : Build all 8 layouts}';

    protected $description = 'Build premium master WordPress sites from the 8 layout designs';

    public function handle(MasterThemeBuilder $builder): int
    {
        if ($this->option('list')) {
            return $this->showList($builder);
        }

        $slug = $this->argument('slug');

        if ($slug) {
            return $this->buildOne($builder, $slug);
        }

        if ($this->option('all')) {
            return $this->buildAll($builder);
        }

        // Show list by default if no args
        return $this->showList($builder);
    }

    private function showList(MasterThemeBuilder $builder): int
    {
        $layouts = $builder->allLayouts();
        $rows = [];

        foreach ($layouts as $slug => $info) {
            $exists = $builder->masterExists($slug);
            $rows[] = [
                $slug,
                $info['name'],
                $info['style'],
                $info['primary'],
                implode(', ', array_slice($info['best_for'], 0, 3)),
                $exists ? '<fg=green>READY</>' : '<fg=yellow>PENDING</>',
            ];
        }

        $this->table(['Slug', 'Name', 'Style', 'Primary Color', 'Best For', 'Status'], $rows);
        $this->info(count($layouts) . ' premium layouts available');

        $ready = collect($layouts)->filter(fn($info) => $builder->masterExists($info['slug']))->count();
        $this->info("{$ready} ready, " . (count($layouts) - $ready) . " pending");

        return 0;
    }

    private function buildOne(MasterThemeBuilder $builder, string $slug): int
    {
        $cfg = $builder->getConfig($slug);
        if (!$cfg) {
            $this->error("Layout not found: {$slug}");
            $this->info('Available: ' . implode(', ', $builder->allSlugs()));
            return 1;
        }

        if ($builder->masterExists($slug)) {
            if ($this->option('fresh')) {
                $this->warn("Destroying existing master-{$slug}...");
                $builder->destroy($slug);
            } else {
                $this->warn("master-{$slug} already exists. Use --fresh to rebuild.");
                return 0;
            }
        }

        $this->info("Building master-{$slug} ({$cfg['name']})...");

        try {
            $result = $builder->build($slug);
            $this->info('Done!');
            $this->table(['Key', 'Value'], [
                ['URL', $result['url']],
                ['Database', $result['db']],
                ['Pages', $result['pages']],
                ['Layout', $result['layout']],
            ]);
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }

    private function buildAll(MasterThemeBuilder $builder): int
    {
        $slugs = $builder->allSlugs();
        $this->info("Building " . count($slugs) . " premium layouts...");

        $bar = $this->output->createProgressBar(count($slugs));
        $bar->start();

        $success = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($slugs as $slug) {
            if ($builder->masterExists($slug) && !$this->option('fresh')) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($builder->masterExists($slug)) {
                $builder->destroy($slug);
            }

            try {
                $builder->build($slug);
                $success++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  Failed {$slug}: " . $e->getMessage());
                $failed++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! Built: {$success}, Skipped: {$skipped}, Failed: {$failed}");

        return $failed > 0 ? 1 : 0;
    }
}
