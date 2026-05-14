<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPostResource;
use App\Models\BlogPost;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopBlogPostsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Top blog posts';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->where('is_published', true)
                    ->orderByDesc('view_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Post')->weight('semibold')->wrap()
                    ->description(fn (BlogPost $r) => $r->slug),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Actions\Action::make('edit')
                    ->url(fn (BlogPost $r) => BlogPostResource::getUrl('edit', ['record' => $r]))
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->paginated(false);
    }
}
