<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\QuestionResource\Pages;
use App\Filament\User\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('quiz_id')
                    ->label('Quiz')
                    ->options(\App\Models\Quiz::all()->pluck('title', 'id'))
                    ->required(),
                Select::make('type')
                    ->label('Type de la question')
                    ->options(\App\Models\Answer::ANSWER_TYPES)
                    ->default('text')
                    ->required()
                    ->live(),
                TextInput::make('title')
                    ->label('Intitulé de la question')
                    ->required(),
                Repeater::make('answers')
                    ->helperText('Ajoutez au moins deux choix correspondant au type de la question.')
                    ->schema([
                        // Type caché, fixé selon la question
                        \Filament\Forms\Components\Hidden::make('type')
                            ->default(fn (Get $get) => $get('../../type')),
                        // Type Texte
                        TextInput::make('title')
                            ->label('Réponse texte')
                            ->placeholder('Entrez une réponse')
                            ->required(fn (Get $get): bool => $get('../../type') === 'text')
                            ->visible(fn (Get $get): bool => $get('../../type') === 'text')
                            ->columnSpanFull(),
                        // Type Audio
                        FileUpload::make('audio_content')
                            ->label('Fichier audio')
                            ->helperText('Formats acceptés : MP3, WAV, OGG, AAC. Taille max : 10MB')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/aac', 'audio/mp3', 'audio/mp4'])
                            ->maxSize(10240)
                            ->required(fn (Get $get): bool => $get('../../type') === 'audio')
                            ->visible(fn (Get $get): bool => $get('../../type') === 'audio')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('quiz-audio')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->reorderable(false)
                            ->appendFiles()
                            ->maxFiles(1),
                        // Type Vidéo
                        FileUpload::make('video_content')
                            ->label('Fichier vidéo')
                            ->helperText('Formats acceptés : MP4, AVI, MOV, WMV. Taille max : 50MB')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv', 'video/webm'])
                            ->maxSize(51200)
                            ->required(fn (Get $get): bool => $get('../../type') === 'video')
                            ->visible(fn (Get $get): bool => $get('../../type') === 'video')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('quiz-video')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->reorderable(false)
                            ->appendFiles()
                            ->maxFiles(1),
                        // Type Fichier
                        FileUpload::make('file_content')
                            ->label('Fichier texte')
                            ->helperText('Formats acceptés : TXT, PDF, DOC, DOCX. Taille max : 10MB')
                            ->acceptedFileTypes(['text/plain', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->required(fn (Get $get): bool => $get('../../type') === 'file')
                            ->visible(fn (Get $get): bool => $get('../../type') === 'file')
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('quiz-files')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->reorderable(false)
                            ->appendFiles()
                            ->maxFiles(1),
                        Toggle::make('is_correct')
                            ->label('Bonne réponse')
                            ->default(false)
                            ->columnSpan(1),
                    ])
                    ->minItems(2)
                    ->required()
                    ->addActionLabel('Ajouter un choix')
                    ->live()
                    ->afterStateUpdated(function ($get, $set) {
                        $value = $get('answers');
                        if (is_array($value)) {
                            $validAnswers = collect($value)->filter(function ($answer) {
                                return isset($answer['title']) && !empty(trim($answer['title']));
                            });
                            
                            $hasCorrectAnswer = collect($value)->contains(function ($answer) {
                                return isset($answer['is_correct']) && $answer['is_correct'] === true;
                            });
                            
                            // Si pas assez de réponses valides, afficher un message
                            if ($validAnswers->count() < 2) {
                                // On pourrait ajouter une notification ici si nécessaire
                            }
                            
                            // Si pas de bonne réponse sélectionnée, afficher un message
                            if (!$hasCorrectAnswer) {
                                // On pourrait ajouter une notification ici si nécessaire
                            }
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $quizId = request()->get('quiz_id') ?? request()->input('quiz_id');
        if (!$quizId) return true;

        $quiz = \App\Models\Quiz::find($quizId);
        if (!$quiz) return true;

        $currentCount = \App\Models\Question::where('quiz_id', $quizId)->count();
        return $currentCount < $quiz->max_questions;
    }
}
