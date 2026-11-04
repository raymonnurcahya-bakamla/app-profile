<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Petugas Penjawab')
                    ->relationship('user', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan (Resmi)',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('published')
                    ->required(),
                Toggle::make('is_published')
                    ->label('Tampilkan di Halaman Publik')
                    ->default(true),
                RichEditor::make('body')
                    ->label('Isi Tanggapan / Jawaban')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penjawab')
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Tanggapan')
                    ->html()
                    ->limit(100),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dijawab Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tulis Jawaban')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['answered_at'] = now();

                        return $data;
                    })
                    ->after(function (QuestionAnswer $record): void {
                        $question = $record->question;
                        if ($question && $record->status === 'published') {
                            $question->update(['status' => 'answered']);

                            // Kirim notifikasi email ke penanya
                            try {
                                Notification::route('mail', $question->email)
                                    ->notify(new QuestionAnsweredNotification($question, $record));
                            } catch (\Throwable $e) {
                                // Ignore email failure in dev
                            }
                        }
                    }),
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
}
