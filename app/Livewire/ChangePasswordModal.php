<?php

namespace App\Livewire;

use App\Models\User;
use Closure;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class ChangePasswordModal extends Component implements HasForms
{
    use InteractsWithForms;

    protected $listeners = ['resetFormData'];

    public ?array $data = [];

    public function render()
    {
        return view('livewire.change-password-modal');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label(__('messages.user.current_password') . ':')
                    ->validationAttribute(__('messages.user.current_password'))
                    ->placeholder(__('messages.user.current_password'))
                    ->password()
                    ->required()
                    ->revealable()
                    ->rule(static function (Get $get): Closure {
                        return static function ($attribute, $value, Closure $fail) use ($get) {
                            $userPassword = auth()->user()->password;
                            if (! password_verify($get('current_password'), $userPassword)) {
                                $fail(__('messages.user.current_password_incorrect'));
                            }
                        };
                    }),
                TextInput::make('new_password')
                    ->label(__('messages.user.new_password') . ':')
                    ->validationAttribute(__('messages.user.new_password'))
                    ->placeholder(__('messages.user.new_password'))
                    ->password()
                    ->required()
                    ->revealable()
                    ->maxLength(255)
                    ->rules(['min:8']),
                TextInput::make('new_password_confirmation')
                    ->label(__('messages.user.confirm_password') . ':')
                    ->validationAttribute(__('messages.user.confirm_password'))
                    ->placeholder(__('messages.user.confirm_password'))
                    ->password()
                    ->required()
                    ->revealable()
                    ->same('new_password')
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    public function save()
    {
        $this->form->validate();
        try {
            /** @var User $user */
            $user = Auth::user();
            $user->password = bcrypt($this->data['new_password']);
            $user->save();

            Session::forget('password_hash_web');
            Auth::login($user);

            $this->form->fill();
            $this->dispatch('close-modal', id: 'change-password-modal');

            Notification::make()
                ->success()
                ->title(__('messages.user.password_updated'))
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
        }
    }

    #[On('close-modal')]
    public function resetFormData()
    {
        $this->reset(['data']);
    }
}
