<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->placeholder('gerado automaticamente, mas pode editar'),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('start_at')
                    ->required(),
                DateTimePicker::make('end_at'),
                TextInput::make('location'),
                TextInput::make('organizer_name'),
                TextInput::make('organizer_email')
                    ->email(),
                TextInput::make('contact_phone')
                    ->tel(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('events')
                    ->maxSize(5120),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'draft' => 'Draft',
                    ])
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('published_at'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
