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
                TextInput::make('title')
                    ->label('Intitulé de la question')
                    ->required(),
                Repeater::make('answers')
                    ->schema([
                        TextInput::make('title')
                            ->label('Réponse')
                            ->required()
                            ->placeholder('Entrez une réponse'),
                        Toggle::make('is_correct')
                            ->label('Bonne réponse')
                            ->default(false),
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
