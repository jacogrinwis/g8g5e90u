<?php

namespace App\Filament\Resources\Tags;

use BackedEnum;
use App\Models\Tag;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\Tags\Pages\ManageTags;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('name')
                //     ->label('Naam')
                //     ->required()
                //     ->maxLength(255)
                //     ->live(onBlur: true)
                //     ->hint(fn($state) => strlen($state ?? '') . '/255')
                //     ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->hint(fn($state) => strlen($state ?? '') . '/255')
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        // maak name lowercase
                        $lower = strtolower($state ?? '');

                        // update het veld zelf (name)
                        $set('name', $lower);

                        // genereer slug van de lowercase name
                        $set('slug', Str::slug($lower));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->hint(fn($state) => strlen($state) . '/255'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTags::route('/'),
        ];
    }
}
