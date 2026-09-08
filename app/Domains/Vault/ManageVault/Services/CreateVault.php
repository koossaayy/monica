<?php

namespace App\Domains\Vault\ManageVault\Services;

use App\Domains\Vault\ManageVaultImportantDateTypes\Services\CreateContactImportantDateType;
use App\Interfaces\ServiceInterface;
use App\Models\Contact;
use App\Models\ContactImportantDate;
use App\Models\LifeEventCategory;
use App\Models\LifeEventType;
use App\Models\MoodTrackingParameter;
use App\Models\Vault;
use App\Models\VaultQuickFactsTemplate;
use App\Services\BaseService;

class CreateVault extends BaseService implements ServiceInterface
{
    public Vault $vault;

    private array $data;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|uuid|exists:accounts,id',
            'author_id' => 'required|uuid|exists:users,id',
            'template_id' => 'nullable|integer|exists:templates,id',
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the permissions that apply to the user calling the service.
     */
    public function permissions(): array
    {
        return [
            'author_must_belong_to_account',
        ];
    }

    /**
     * Create a vault.
     */
    public function execute(array $data): Vault
    {
        $this->validateRules($data);
        $this->data = $data;

        $this->createVault();
        $this->createUserContact();
        $this->populateDefaultContactImportantDateTypes();
        $this->populateMoodTrackingParameters();
        $this->populateDefaultLifeEventCategories();
        $this->populateDefaultQuickVaultTemplateEntries();

        return $this->vault;
    }

    private function createVault(): void
    {
        // the vault default's template should be the first template in the
        // account, if it exists
        $template = $this->account()->templates()->first();

        $this->vault = Vault::create([
            'account_id' => $this->data['account_id'],
            'type' => $this->data['type'],
            'name' => $this->data['name'],
            'description' => $this->valueOrNull($this->data, 'description'),
            'default_template_id' => $template ? $template->id : null,
        ]);
    }

    private function createUserContact(): void
    {
        $contact = Contact::create([
            'vault_id' => $this->vault->id,
            'first_name' => $this->author->first_name,
            'last_name' => $this->author->last_name,
            'can_be_deleted' => false,
            'template_id' => $this->vault->default_template_id,
        ]);

        $this->vault->users()->save($this->author, [
            'permission' => Vault::PERMISSION_MANAGE,
            'contact_id' => $contact->id,
        ]);
    }

    private function populateDefaultContactImportantDateTypes(): void
    {
        (new CreateContactImportantDateType)->execute([
            'account_id' => $this->data['account_id'],
            'author_id' => $this->author->id,
            'vault_id' => $this->vault->id,
            'label' => trans('Birthdate'),
            'internal_type' => ContactImportantDate::TYPE_BIRTHDATE,
            'can_be_deleted' => false,
        ]);

        (new CreateContactImportantDateType)->execute([
            'account_id' => $this->data['account_id'],
            'author_id' => $this->author->id,
            'vault_id' => $this->vault->id,
            'label' => trans('Deceased date'),
            'internal_type' => ContactImportantDate::TYPE_DECEASED_DATE,
            'can_be_deleted' => false,
        ]);
    }

    private function populateMoodTrackingParameters(): void
    {
        MoodTrackingParameter::create([
            'vault_id' => $this->vault->id,
            'label' => null,
            'label_translation_key' => trans_key(__('🥳 Awesome')),
            'position' => 1,
            'hex_color' => 'bg-lime-500',
        ]);
        MoodTrackingParameter::create([
            'vault_id' => $this->vault->id,
            'label' => null,
            'label_translation_key' => trans_key(__('😀 Good')),
            'position' => 2,
            'hex_color' => 'bg-lime-300',
        ]);
        MoodTrackingParameter::create([
            'vault_id' => $this->vault->id,
            'label' => null,
            'label_translation_key' => trans_key(__('😐 Meh')),
            'position' => 3,
            'hex_color' => 'bg-cyan-600',
        ]);
        MoodTrackingParameter::create([
            'vault_id' => $this->vault->id,
            'label' => null,
            'label_translation_key' => trans_key(__('😔 Bad')),
            'position' => 4,
            'hex_color' => 'bg-orange-300',
        ]);
        MoodTrackingParameter::create([
            'vault_id' => $this->vault->id,
            'label' => null,
            'label_translation_key' => trans_key(__('😩 Awful')),
            'position' => 5,
            'hex_color' => 'bg-red-700',
        ]);
    }

    private function populateDefaultLifeEventCategories(): void
    {
        // transportation category
        $category = LifeEventCategory::create([
            'vault_id' => $this->vault->id,
            'position' => 1,
            'label' => null,
            'label_translation_key' => trans_key(__('Transportation')),
            'can_be_deleted' => true,
        ]);

        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 1,
            'label' => null,
            'label_translation_key' => trans_key(__('Rode a bike')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 2,
            'label' => null,
            'label_translation_key' => trans_key(__('Drove')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 3,
            'label' => null,
            'label_translation_key' => trans_key(__('Walked')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 4,
            'label' => null,
            'label_translation_key' => trans_key(__('Took the bus')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 5,
            'label' => null,
            'label_translation_key' => trans_key(__('Took the metro')),
            'can_be_deleted' => true,
        ]);

        // social category
        $category = LifeEventCategory::create([
            'vault_id' => $this->vault->id,
            'position' => 2,
            'label' => null,
            'label_translation_key' => trans_key(__('Social')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 1,
            'label' => null,
            'label_translation_key' => trans_key(__('Ate')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 2,
            'label' => null,
            'label_translation_key' => trans_key(__('Drank')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 3,
            'label' => null,
            'label_translation_key' => trans_key(__('Went to a bar')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 4,
            'label' => null,
            'label_translation_key' => trans_key(__('Watched a movie')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 5,
            'label' => null,
            'label_translation_key' => trans_key(__('Watched TV')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 6,
            'label' => null,
            'label_translation_key' => trans_key(__('Watched a tv show')),
            'can_be_deleted' => true,
        ]);

        // sport category
        $category = LifeEventCategory::create([
            'vault_id' => $this->vault->id,
            'position' => 3,
            'label' => null,
            'label_translation_key' => trans_key(__('Sport')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 1,
            'label' => null,
            'label_translation_key' => trans_key(__('Ran')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 2,
            'label' => null,
            'label_translation_key' => trans_key(__('Played soccer')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 3,
            'label' => null,
            'label_translation_key' => trans_key(__('Played basketball')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 4,
            'label' => null,
            'label_translation_key' => trans_key(__('Played golf')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 5,
            'label' => null,
            'label_translation_key' => trans_key(__('Played tennis')),
            'can_be_deleted' => true,
        ]);

        // work category
        $category = LifeEventCategory::create([
            'vault_id' => $this->vault->id,
            'position' => 4,
            'label' => null,
            'label_translation_key' => trans_key(__('Work')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 1,
            'label' => null,
            'label_translation_key' => trans_key(__('Took a new job')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 2,
            'label' => null,
            'label_translation_key' => trans_key(__('Quit job')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 3,
            'label' => null,
            'label_translation_key' => trans_key(__('Got fired')),
            'can_be_deleted' => true,
        ]);
        LifeEventType::create([
            'life_event_category_id' => $category->id,
            'position' => 4,
            'label' => null,
            'label_translation_key' => trans_key(__('Had a promotion')),
            'can_be_deleted' => true,
        ]);
    }

    private function populateDefaultQuickVaultTemplateEntries(): void
    {
        VaultQuickFactsTemplate::create([
            'vault_id' => $this->vault->id,
            'label_translation_key' => trans_key(__('Hobbies')),
            'position' => 1,
        ]);

        VaultQuickFactsTemplate::create([
            'vault_id' => $this->vault->id,
            'label_translation_key' => trans_key(__('Food preferences')),
            'position' => 2,
        ]);
    }
}