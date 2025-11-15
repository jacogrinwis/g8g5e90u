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
                    ->label('Categorie')
                    ->helperText('Kies een categorie voor deze post. ')
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
                    ->helperText('Wordt automatisch aangemaakt op basis van de titel, maar kan handmatig aangepast worden.')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->hint(fn($state) => strlen($state) . '/255'),

                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('excerpt')
                    ->label('Samenvatting')
                    ->helperText('Optioneel veld voor een korte samenvatting. Als je niets invult, wordt automatisch de eerste 255 tekens van de content gebruikt.')
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
                    )
                    ->required()
                    ->columnSpanFull(),

                Select::make('tags')
                    ->label('Tags')
                    ->helperText('Kies één of meerdere tags voor deze post.')
                    ->relationship('tags', 'name')
                    ->multiple(),
            ]);
    }
}
