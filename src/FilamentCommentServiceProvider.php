<?php

namespace Xentixar\FilamentComment;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCommentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-comments';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->copyAndRegisterServiceProviderInApp()
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('spatie/laravel-dynamic-servers');
            });

        $package
            ->hasViews()
            ->hasTranslations()
            ->hasConfigFile([
                'filament-comments',
            ])
            ->hasMigrations([
                'create_filament_comments_table',
                'create_filament_comment_activities_table',
            ]);
    }

    public function packageBooted(): void
    {
        $this->app->register(\Xentixar\FilamentComment\FilamentCommentServiceProvider::class);

        Livewire::component('list-comments', \Xentixar\FilamentComment\Livewire\ListComments::class);
        Livewire::component('comment', \Xentixar\FilamentComment\Livewire\Comment::class);
        Livewire::component('add-comment', \Xentixar\FilamentComment\Livewire\AddComment::class);
    }
}
