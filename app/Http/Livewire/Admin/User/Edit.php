<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Agency;
use App\Models\Connection;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZxcvbnPhp\Zxcvbn;

class Edit extends Component
{
    public User $user;
    public array $blocked_connections = [];

    public string $password = '';

    public int $strengthScore = 0;

    public array $strengthLevels = [
        1 => 'Very Weak',
        2 => 'Okay but...',
        3 => 'Good',
        4 => 'Strong',
    ];

    public $connections = [];

    public array $mediaToRemove = [];

    public array $listsForFields = [];

    public array $mediaCollections = [];

    protected $old_balance = 0;

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);

        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn ($item) => $item['uuid'] === $media['uuid'])->toArray();

        $this->mediaToRemove[] = $media['uuid'];
    }

    public function getMediaCollection($name)
    {
        return $this->mediaCollections[$name];
    }

    public function mount($id)
    {
        $this->user  = User::find($id);
        $this->old_balance = $this->user->balance;
        $this->initListsForFields();
        $this->mediaCollections = [
            'user_logo' => $this->user->logo,
        ];
        // Initialize blocked_connections from user's comma separated string
        $this->blocked_connections = $this->user->blocked_connections
            ? array_filter(explode(',', $this->user->blocked_connections))
            : [];
    }

    public function render()
    {
        return view('livewire.admin.user.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        // Save blocked_connections as comma separated string
        $this->user->blocked_connections = implode(',', $this->blocked_connections);
        $oldBalance = $this->user->getOriginal('balance');
        $newBalance = $this->user->balance;

        //$this->user->password = $this->password;
        if($this->password && !empty($this->password) && $this->password != '') {
            $this->user->password = $this->password;
        }
        if($this->user->active != 1 && $this->user->type == 'api')
        {
            $this->user->tokens()->delete();
        }
        $this->user->save();
        if ($this->user->isDirty('balance') && auth()->user()->type == 'white-admin' || auth()->user()->type == 'white-user') {
            $balanceDifference = $newBalance - $oldBalance;
            $agency = Agency::where('id', auth()->user()->agency_id)->first();

            // Check if balance increased
            if ($balanceDifference > 0) {
                // Check if the final amount is less than the agency amount
                if (($agency->balance - $balanceDifference) < 0) {
                    // Handle insufficient agency balance
                    // You can throw an error message or handle it according to your application's logic
                    // For simplicity, let's throw an exception here
                    $this->addError('user.balance', 'Insufficient agency balance');
                }

                // Deduct the increased amount from the agency balance
                $agency->balance -= $balanceDifference;
            } elseif ($balanceDifference < 0) {
                // Add the decreased amount to the agency balance
                $agency->balance -= $balanceDifference;
            }
            $agency->save();
        }
        $this->syncMedia();
        return redirect()->route('admin.users.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
            ->update(['model_id' => $this->user->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'user.name' => [
                'string',
                'required',
            ],
            'user.email' => [
                'email:rfc',
                'required',
                'unique:users,email,' . $this->user->id,
            ],
            'password' => [
                'string',
            ],
            'user.air_arabia_markup' => [
                'nullable'
            ],
            'user.air_arabia_markup_type' => [
                'nullable'
            ],
            'user.fly_dubai_markup' => [
                'nullable'
            ],
            'user.fly_dubai_markup_type' => [
                'nullable'
            ],
            'user.type' => [
                'required'
            ],
            'user.restricted' => [
                'nullable',
            ],
            'user.balance' => [
                'numeric',
                'nullable'
            ],
            'mediaCollections.user_logo' => [
                'array',
                'nullable',
            ],
            'mediaCollections.user_logo.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'user.is_approved' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'user.active' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'user.auto_pricing' => [
                'nullable'
            ]
        ];
    }
    public function generatePassword(): void
    {
        $lowercase = range('a', 'z');
        $uppercase = range('A', 'Z');
        $digits = range(0,9);
        $special = ['!', '@', '#', '$', '%', '^', '*'];
        $chars = array_merge($lowercase, $uppercase, $digits, $special);
        $length = 12;
        do {
            $password = array();

            for ($i = 0; $i <= $length; $i++) {
                $int = rand(0, count($chars) - 1);
                $password[] = $chars[$int];
            }

        } while (empty(array_intersect($special, $password)));

        $this->setPasswords(implode('', $password));
    }

    private function setPasswords($value): void
    {
        $this->password = $value;
        $this->updatedPassword($value);
    }
    public function updatedPassword($value)
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['type']  = $this->user::TYPE_SELECT;
        $this->connections = Connection::query()->get();
    }
}
