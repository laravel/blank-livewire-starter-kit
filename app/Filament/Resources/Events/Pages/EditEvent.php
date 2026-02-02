<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use App\Notifications\EventApproved;
use App\Notifications\EventRejected;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Notification;
use Filament\Forms\Components\Textarea;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->action(function (Event $record) {
                    $record->status = Event::STATUS_APPROVED;
                    $record->published_at = now();
                    $record->save();

                    Notification::route('mail', $record->organizer_email)
                        ->notify(new EventApproved($record));

                    $this->notify('success', 'Event approved and organizer notified.');
                })
                ->visible(fn ($record) => $record->status !== Event::STATUS_APPROVED),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->modalHeading('Reject event')
                ->form([
                    Textarea::make('reason')
                        ->required()
                        ->label('Reason for rejection'),
                ])
                ->action(function (Event $record, array $data) {
                    $record->status = Event::STATUS_REJECTED;
                    $record->save();

                    Notification::route('mail', $record->organizer_email)
                        ->notify(new EventRejected($record, $data['reason'] ?? null));

                    $this->notify('success', 'Event rejected and organizer notified.');
                })
                ->visible(fn ($record) => $record->status !== Event::STATUS_REJECTED),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
