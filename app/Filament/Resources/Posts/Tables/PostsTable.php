<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatusEnum;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('excerpt')
                    ->label('Korte tekst')
                    ->searchable()
                    ->limit(50) // laat alleen de eerste 50 tekens zien
                    ->wrap()    // zodat lange teksten netjes worden afgebroken
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('category.name')
                    ->label('Categorie')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('tags.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                // Select::make('status')
                //     ->options(PostStatusEnum::class)
                //     ->required(),
                // ->toggleable(isToggledHiddenByDefault: false),

                // Select::make('status')
                //     ->label('Status')
                //     ->options(PostStatusEnum::class),
                // ->searchable(),
                // ->toggleable(isToggledHiddenByDefault: false),

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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
