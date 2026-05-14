<?php

namespace App\Filament\Resources;

use App\Enums\BlogCategory;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static ?string                     $navigationLabel = 'Blog Posts';
    protected static string | \UnitEnum | null   $navigationGroup = 'Content';
    protected static ?int                        $navigationSort  = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->label('')->disk('public')->square()->size(56),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()->weight('semibold')->wrap()
                    ->description(fn (BlogPost $r) => $r->slug),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        BlogCategory::Bridal       => 'success',
                        BlogCategory::Tutorials    => 'primary',
                        BlogCategory::Trends       => 'warning',
                        BlogCategory::Care         => 'info',
                        BlogCategory::BehindScenes => 'gray',
                        default                    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof BlogCategory ? $state->label() : $state),
                Tables\Columns\TextColumn::make('view_count')->label('Views')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(collect(BlogCategory::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Post')->columns(2)->schema([
                Forms\Components\TextInput::make('title')
                    ->required()->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('slug', Str::slug($state));
                        $set('meta_title', $state);
                    })
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)->columnSpanFull(),
                Forms\Components\Select::make('category')
                    ->options(collect(BlogCategory::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                    ->required(),
                Forms\Components\TagsInput::make('tags')
                    ->placeholder('Add tag + Enter')->separator(','),
                Forms\Components\Textarea::make('excerpt')
                    ->rows(2)->maxLength(300)->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->required()->fileAttachmentsDirectory('blog')->columnSpanFull(),
            ]),
            FormSection::make('Cover image')->columns(2)->schema([
                Forms\Components\FileUpload::make('cover_image')
                    ->label('Cover image (hand-only, no faces)')
                    ->image()->disk('public')->directory('blog'),
                Forms\Components\TextInput::make('cover_image_alt')->label('Image alt text'),
            ]),
            FormSection::make('Publishing')->columns(2)->schema([
                Forms\Components\Toggle::make('is_published')->label('Published')->live()->default(false),
                Forms\Components\DateTimePicker::make('published_at')->label('Publish date')->default(now()),
            ]),
            FormSection::make('Related products')->schema([
                Forms\Components\Select::make('products')
                    ->relationship('products', 'name')
                    ->multiple()->searchable()->preload()
                    ->helperText('Products shown in the "Related sets" block at the bottom of the post.'),
            ]),
            FormSection::make('SEO')->collapsed()->schema([
                Forms\Components\TextInput::make('target_keyword')
                    ->label('Target keyword')->helperText('Primary keyword this post targets.'),
                Forms\Components\TextInput::make('meta_title')->label('Meta title')->maxLength(70),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta description')->rows(2)->maxLength(160),
                Forms\Components\FileUpload::make('og_image')
                    ->label('OG image (1200×630)')->image()->disk('public')->directory('blog/og'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
