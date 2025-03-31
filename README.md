# Filament Comments
A powerful recursive comments system for Filament, allowing you to add nested commenting functionality to your Filament resources and custom Livewire pages.

<div align="center">
    <img src="banner.svg" alt="Filament Comments Banner" width="100%">
</div>
<p class="flex items-center justify-center">
    <a href="https://packagist.org/packages/xentixar/filament-comments">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/xentixar/filament-comments.svg?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/xentixar/filament-comments/stats">
        <img alt="Packagist" src="https://img.shields.io/packagist/dt/xentixar/filament-comments.svg?style=for-the-badge">
    </a>
    <a href="#">
        <img alt="Packagist" src="https://img.shields.io/packagist/l/xentixar/filament-comments.svg?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/xentixar/filament-comments">
        <img alt="Packagist" src="https://img.shields.io/github/stars/xentixar/filament-comments?style=for-the-badge">
    </a>
    <a href="https://github.com/xentixar/filament-comments/forks">
        <img alt="Packagist" src="https://img.shields.io/github/forks/xentixar/filament-comments?style=for-the-badge">
    </a>
</p>

## Overview

Filament Comments is a robust and feature-rich commenting system designed for both Filament admin panels and custom Livewire pages. It provides a seamless way to implement nested comments, discussions, and feedback mechanisms across your application.

### Key Benefits

- **Versatile Integration**: Works seamlessly in both Filament admin panels and custom Livewire pages
- **Real-time Updates**: Powered by Livewire for instant comment updates without page refreshes
- **Flexible Architecture**: Support for multiple comment types and customizable configurations
- **Activity Tracking**: Monitor comment interactions and user engagement
- **Modern UI**: Clean and intuitive interface that matches Filament's design language
- **Table Support**: Built-in support for Filament tables in both admin and custom pages

## Features

- Easy integration with Filament admin panel
- Recursive (nested) comments support
- Real-time comments using Livewire
- Activity tracking for comments
- Configurable and customizable
- Supports multiple comment types
- Built-in migrations and configurations
- Rich text editor support
- Comment moderation capabilities
- User mentions and notifications
- Comment threading and replies
- Activity logging and analytics

## Requirements

- PHP 8.1+
- Laravel 10.0+
- Filament 3.0+

## Installation

You can install the package via composer:

```bash
composer require xentixar/filament-comments
```

Publish the service provider:

```bash
php artisan vendor:publish --provider="Xentixar\FilamentComment\FilamentCommentServiceProvider"
```

Run the migrations:
```bash
php artisan migrate
```

## Usage

### Adding Comments to Your Models

To enable comments on your model, use the `HasFilamentComment` trait and implement `Commentable` contract:

```php
use Xentixar\FilamentComment\Models\Traits\HasFilamentComment;
use Xentixar\FilamentComment\Contracts\Commentable;

class Post extends Model implements Commentable
{
    use HasFilamentComment;
    
    protected $fillable = ['title', 'content', 'user_id'];
}
```

### Using in Filament Admin Panel

To add the comment preview functionality to your Filament resource tables:

```php
use Xentixar\FilamentComment\Tables\Actions\CommentPreview;

class PostResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Your columns...
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                CommentPreview::make()
            ]);
    }
}
```

### Using in Custom Livewire Pages

You can also use the comment system in your custom Livewire pages:

```php
use Xentixar\FilamentComment\Tables\Actions\CommentPreview;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class CustomPage extends Component implements HasTable
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Your columns...
            ])
            ->actions([
                CommentPreview::make()
            ]);
    }
}
```

### Configuration

You can publish and customize the configuration file:

```bash
php artisan vendor:publish --provider="Xentixar\FilamentComment\FilamentCommentServiceProvider" --tag="config"
```

This will publish the configuration file to `config/filament-comments.php`. Here you can customize various aspects of the comments system:

```php
return [
    'comment' => [
        // The table name that will be used to store the comments
        'table' => 'filament_comments',
    ],
    'activity' => [
        // The table name that will be used to store the comment activities
        'table' => 'filament_comment_activities',
    ],
    'user' => [
        // The model that will be used to store the users
        'model' => \App\Models\User::class,

        // The table name that will be used to store the users
        'table' => 'users',
    ],
];
```

#### Configuration Options

- **Comment Table**: Customize the table name for storing comments
- **Activity Table**: Customize the table name for storing comment activities
- **User Model**: Specify your application's user model
- **User Table**: Customize the table name for storing users

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

For support, please open an issue in the [GitHub repository](https://github.com/xentixar/filament-comments/issues).

## Credits

- [xentixar](https://github.com/xentixar)

