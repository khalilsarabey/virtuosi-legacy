<?php

namespace App\Livewire\Admin\User;

use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use ZxcvbnPhp\Zxcvbn;
class Create extends Component
{
    public array $blocked_connections = [];
    public User $user;

    public string $password = '';
    public int $strengthScore = 0;

    public array $strengthLevels = [
        1 => 'Very Weak',
        2 => 'Okay but...',
        3 => 'Good',
        4 => 'Strong',
    ];

    public array $mediaToRemove = [];

    public array $listsForFields = [];

    public array $mediaCollections = [];

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

    public function mount(User $user)
    {
        $this->user              = $user;
        $this->user->type        = auth()->user()->type == 'white-admin' ? 'white' : 'agency';
        $this->user->restricted  = '0';
        $this->user->auto_pricing = '0';
        $this->user->balance     = '0';
        $this->user->is_approved = '1';
        $this->user->active      = '0';
        $this->initListsForFields();
        $this->blocked_connections = [];
    }

    public function render()
    {
        return view('livewire.admin.user.create')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->user->blocked_connections = implode(',', $this->blocked_connections);
        $this->user->password = $this->password;
        $this->user->agency_id = auth()->user()->agency_id;
        $this->user->email_verified_at = date('Y-m-d H:i:s');
        if(auth()->user()->type == 'api') {
            $this->user->balance = 10000000;
        }
        $this->user->save();
        if(auth()->user()->type == 'white-admin') {
            $agency = Agency::where('id', auth()->user()->agency_id)->first();
            $agency->balance = $agency->balance - $this->user->balance;
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
                'unique:users,email',
            ],
            'password' => [
                'string',
                'required',
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
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['type'])),
            ],
            'user.restricted' => [
                'nullable',
            ],
            'user.balance' => [
                'numeric',
                'nullable',
                'max:' . get_agency_balance()
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
            'user.agency_id' => [
                'nullable'
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

    public function updatedPassword($value)
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
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

    protected function initListsForFields(): void
    {
        $this->listsForFields['type']  = $this->user::TYPE_SELECT;
        if(!config('features.whitelabel')) {
            unset($this->listsForFields['type']['white-admin']);
        }
        if(!config('features.api')) {
            unset($this->listsForFields['type']['api']);
        }
        if(auth()->user()->type != 'admin') {
            $this->listsForFields['type'] = [
                'white'         => 'Agency',
                'white-admin'   => 'Admin',
            ];
        }
        // Fetch connections for the view
        $this->connections = \App\Models\Connection::query()->get();
    }
}
