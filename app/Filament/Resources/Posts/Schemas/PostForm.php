<?php

namespace App\Filament\Resources\Posts\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Enums\PostStatusEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\ModalTableSelect;
use App\Filament\Tables\CategoriesTable;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ModalTableSelect::make('category_id')
                    ->relationship('category', 'name')
                    ->tableConfiguration(CategoriesTable::class)
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->hint(fn($state) => strlen($state ?? '') . '/255')
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->hint(fn($state) => strlen($state) . '/255'),

                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('excerpt')
                    ->label('Korte tekst')
                    ->maxLength(255)
                    ->rows(5)
                    ->columnSpanFull()
                    ->reactive()
                    ->hint(fn($state) => strlen($state) . '/255'),

                Radio::make('status')
                    ->label('Status')
                    ->options(
                        collect(PostStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [
                                $case->value => ucfirst($case->value)
                            ])
                    ),

                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple(),
            ]);
    }
}
