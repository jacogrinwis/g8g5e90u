<?php

namespace App\Filament\Resources\Posts\Schemas;

use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Enums\PostStatusEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use App\Filament\Tables\CategoriesTable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\ModalTableSelect;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    // ->description('Vul de basisinformatie van de post in.')
                    ->schema([
                        ModalTableSelect::make('category_id')
                            ->label('Categorie')
                            ->helperText('Kies een categorie voor deze post. ')
                            ->relationship('category', 'name')
                            ->tableConfiguration(CategoriesTable::class)
                            ->required()
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
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        Section::make('Status')
                            // ->description('Vul de basisinformatie van de post in.')
                            ->schema([
                                Radio::make('status')
                                    // ->label('Status')
                                    ->hiddenLabel()
                                    ->options(
                                        collect(PostStatusEnum::cases())
                                            ->mapWithKeys(fn($case) => [
                                                $case->value => ucfirst($case->value)
                                            ])
                                    )
                                    ->default(PostStatusEnum::DRAFT->value)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Uitgelichte afbeelding')
                            // ->description('Vul de basisinformatie van de post in.')
                            ->schema([
                                FileUpload::make('featured_image')
                                    ->hiddenLabel()
                                    // ->helperText('Upload een uitgelichte afbeelding voor deze post.')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Tags')
                            // ->description('Vul de basisinformatie van de post in.')
                            ->schema([
                                Select::make('tags')
                                    // ->label('Tags')
                                    ->hiddenLabel()
                                    ->helperText('Kies één of meerdere tags voor deze post.')
                                    ->relationship('tags', 'name')
                                    ->multiple(),
                            ]),
                    ]),
            ])->columns(3);
    }
}
